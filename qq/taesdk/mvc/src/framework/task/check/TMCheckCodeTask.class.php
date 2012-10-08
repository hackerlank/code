<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2008 BY  TENCENT  CORPORATION.  ALL RIGHTS
 *     RESERVED.   NO  PART  OF THIS PROGRAM  OR  PUBLICATION  MAY
 *     BE  REPRODUCED,   TRANSMITTED,   TRANSCRIBED,   STORED  IN  A
 *     RETRIEVAL SYSTEM, OR TRANSLATED INTO ANY LANGUAGE OR COMPUTER
 *     LANGUAGE IN ANY FORM OR BY ANY MEANS, ELECTRONIC, MECHANICAL,
 *     MAGNETIC,  OPTICAL,  CHEMICAL, MANUAL, OR OTHERWISE,  WITHOUT
 *     THE PRIOR WRITTEN PERMISSION OF :
 *
 *                        TENCENT  CORPORATION
 *
 *       Advertising Platform R&D Team, Advertising Platform & Products
 *       Tencent Ltd.
 *---------------------------------------------------------------------------
 */

define('CHECKER_ONE_SPACE', ' ');
define('CHECKER_METHOD_KEYWORD', '(abstract |final )?(public |protected |private )+(static )?');

/**
 * TMCheckCodeTask
 * 检查代码的任务
 *
 * @package sdk.mvc.src.framework.task.check
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMCheckCodeTask.class.php 2011-4-21 by ianzhang    
 */
class TMCheckCodeTask extends TMCommandApplicationTask {
    protected $config = null;

    protected $errorMessages = array();
    
    /**
     * @see TMTask
     */
    protected function configure() {
        $this->addArguments ( array (new TMCommandArgument ( 'path', TMCommandArgument::OPTIONAL, "The checked code's path" ) ) );
        
        $this->aliases = array ('ckc');
        $this->namespace = 'check';
        $this->name = 'code';
        $this->briefDescription = 'Checks the codes';

        $this->detailedDescription = <<<EOF
The [check:code|INFO] task Checks the codes.

  [./taesdk.php check:code|INFO]
it checks all the codes in your project.

EOF;
    }

    /**
     * 增加扫描规则
     * @param string $info
     * @param string $reg
     * @param string $message
     * @param int $adjustLine
     * @param int $baseOffline
     */
    protected function addRule($info, $reg, $message, $adjustLine = 0, $baseOffline = 0)
    {
        $this->checkRule[] = array(
            'reg' => $reg,
            'message' => $message,
            'adjustLine' => $adjustLine,
            'baseOffline' => $baseOffline,
        );
    }

    /**
     * 执行任务
     * @param array $arguments
     */
    protected function execute($arguments = array())
    {
        $rules = array(
            'methodNeedComment' => array("/(?<!\/)\n".CHECKER_ONE_SPACE."*".CHECKER_METHOD_KEYWORD."function/", 'all class methods need comment', 1, 1),
        );

        foreach ($rules as $key => $rule)
        {
            $rule[2] = isset($rule[2]) ? $rule[2] : null;
            $rule[3] = isset($rule[3]) ? $rule[3] : null;
            $this->addRule($key, $rule[0], $rule[1], $rule[2], $rule[3]);
        }

        $path = ROOT_PATH;
        if(isset($arguments["path"])){
            $path = $arguments["path"];
        }
        if($path[0] != "/")
        {
            $path = getcwd()."/".$path;
        }
        
        $finder = new TMFinder($path);
        $finder->addIgnoreFolders(array('.', '..', '.svn'));
        $finder->setFormat('/(\.class\.php)|(Controller\.php)$/');

        $findFiles = $finder->execute();
        
        foreach ($findFiles as $file)
        {
            $this->doCheck($file);
        }

        $this->displayMessage();
    }

    /**
     * Keep the message
     *
     * @param string $file        the address of the file
     * @param string $message     the messgae need to display
     * @param int $lineNum        the address of the file
     */
    protected function keepMessage($file, $message, $lineNum)
    {
        if (!is_array($lineNum))
        {
            $lineNum = array($lineNum);
        }

        foreach ($lineNum as $line)
        {
            $this->errorMessages[] = realpath($file).': on line '.$line.' : '.$message."\n";
        }
    }

    /**
     * 检查匹配规则
     * @param string $file
     * @param string $fileContent
     */
    protected function checkRegRule($file, $fileContent)
    {
        foreach ($this->checkRule as $rule)
        {
            $result = preg_match($rule['reg'], $fileContent, $matchs);

            if ($result)
            {
                $lineNum = array();
                $subContent = preg_split($rule['reg'], $fileContent);

                $adjustLine = isset($rule['adjustLine']) ? $rule['adjustLine'] : 0;

                $baseOffline = isset($rule['baseOffline']) ? $rule['baseOffline'] : 0;

                foreach ($subContent as $key => $value)
                {
                    if ($key > 0)
                    {
                        $lineNum[$key] = substr_count($value, "\n") + $lineNum[$key - 1] + $adjustLine;
                    }
                    else
                    {
                        $lineNum[$key] = substr_count($value, "\n") + 1 + $baseOffline;
                    }
                }

                array_pop($lineNum);
                $this->keepMessage($file, $rule['message'], $lineNum);
            }
        }
    }

    /**
     * display the message
     */
    protected function displayMessage()
    {
        $this->log($this->errorMessages);
    }

    /**
     * check one file's code indentation
     *
     * @param string $file         the address of the file
     */
    protected function doCheck($file)
    {
        $fileContent = file_get_contents($file);

        $this->checkRegRule($file, $fileContent);

        $contentArray = preg_split('/\n/', $fileContent);

        $lineNum = 0;

        foreach ($contentArray as $key => $row)
        {

            $lineNum++;

            /**
             * match the end of command
             */
            if (preg_match('/ *\*\//', $row))
            {
                $i = $key;
                $totalCommandParam = $totalReturn = array();

                /**
                 * match the method header
                 */
                preg_match('/^.*function ([a-zA-Z0-9]+)\((.*)\)$/', $contentArray[$key + 1], $matchHeader);

                /**
                 * match the start of command
                 */
                while (!preg_match('/ *\/\*\*/', $contentArray[$i], $match))
                {
                    if ($i > 0)
                    {
                        $i--;
                    }
                    else
                    {
                        break;
                    }
                    /**
                     * keep param information in command
                     */
                    if (preg_match('/@param/', $contentArray[$i]))
                    {
                        if (preg_match('/@param +([^ ]*) +([^ ]*)/', $contentArray[$i], $subMatch))
                        {
                            $totalCommandParam[$subMatch[2]] = array(
                'line' => $i + 2,
                'type' => $subMatch[1],
                'name' => $subMatch[2],
                            );
                        }
                        else
                        {
                            $this->keepMessage($file, 'the param type should be "@param Type $variableName description"', $i + 1);
                            continue;
                        }
                    }

                    /**
                     * keep return information in command
                     */
                    if (preg_match('/@return +([^ ]*)/', $contentArray[$i], $subMatch))
                    {
                        $totalReturn[] = array(
              'line' => $i + 1,
              'type' => $subMatch[1],
                        );
                    }
                }

                if (!$matchHeader)
                {
                    // this is const;
                    // hack
                    continue;
                }
                $methodParam = preg_split('/, ?/', $matchHeader[2]);
                $totalMethodParam = array();

                /**
                 * keep param information in method head
                 */
                if ('' != $matchHeader[2])
                {
                    foreach ($methodParam as $value)
                    {
                        /**
                         * Type $value = defaultValue
                         */
                        if (preg_match('/([a-zA-Z0-9]+) &?(\$[a-zA-Z0-9]+) ?=?/', $value, $subMatch))
                        {
                            $totalMethodParam[$subMatch[2]] = array(
                'line' => $key + 2,
                'type' => $subMatch[1],
                'name' => $subMatch[2],
                            );
                        }

                        /**
                         * $value = defaultValue
                         */
                        else if (preg_match('/&?(\$[a-zA-Z0-9]+) ?= ?.*/', $value, $subMatch))
                        {
                            $totalMethodParam[$subMatch[1]] = array(
                'line' => $key + 2,
                'type' => '',
                'name' => $subMatch[1],
                            );
                        }

                        /**
                         * $value
                         */
                        else
                        {
                            if ('&' == $value[0])
                            {
                                $value = substr($value, 1);
                            }
                            $value = ltrim($value);
                            $totalMethodParam[$value] = array(
                'line' => $key + 2,
                'type' => '',
                'name' => $value,
                            );
                        }
                    }
                }

                if (empty($totalReturn))
                {
                    //$this->keepMessage($file, 'must use @return in command', $key + 2);
                }

                /**
                 * check the style
                 */
                if (count($totalCommandParam) != count($totalMethodParam))
                {
                    $this->keepMessage($file, 'the param number is not match between command and method defineded', $key + 2);
                }
                else
                {
                    foreach ($totalMethodParam as $subKey => $subValue)
                    {
                        if (!isset($totalCommandParam[$subKey]))
                        {
                            $this->keepMessage($file, $subKey." is not in command as '@param Type ...'", $key + 2);
                            continue;
                        }

                        if ('unknown_type' == $totalCommandParam[$subKey]['type'])
                        {
                            $this->keepMessage($file, 'the param type cannot be "unknown_type" in command', $totalCommandParam[$subKey]['line'] - 1);
                            continue;
                        }

                        if (!in_array($totalCommandParam[$subKey]['type'], array('mixed', 'int', 'string', 'boolean')) && $subValue['type'] != $totalCommandParam[$subKey]['type'])
                        {
                            // type hint would case problem in an abstract method @see: \symfony-1.1.4\lib\action\sfComponent.class.php, because symfony didn't do type hint.
                            // hack here
                            if ('$request' == $subValue['name'])
                            {
                                continue;
                            }
                            // remove the type hint message here
                            //$this->keepMessage($file, 'the varible type in command is not the same as the one defineded in method', $key + 2);
                        }
                    }
                }
            }
        }
    }
}
?>