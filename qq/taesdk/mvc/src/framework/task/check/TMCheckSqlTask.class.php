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

/**
 * TMCheckCodeTask
 * 检查代码的任务
 *
 * @package sdk.mvc.src.framework.task.check
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMCheckCodeTask.class.php 2011-4-21 by ianzhang
 */
class TMCheckSqlTask extends TMCommandApplicationTask {
    const NO_GOOD_SQL = 1;
    const GOOD_SQL = 2;

    protected $config = null;

    protected $errorMessages = array();

    /**
     * @see TMTask
     */
    protected function configure() {
        $this->addArguments ( array (new TMCommandArgument ( 'path', TMCommandArgument::OPTIONAL, "The checked code's path" ) ) );

        $this->aliases = array ('ckq');
        $this->namespace = 'check';
        $this->name = 'sql';
        $this->briefDescription = 'Checks the sqls';

        $this->detailedDescription = <<<EOF
The [check:code|INFO] task Checks the codes.

  [./taesdk.php check:code|INFO]
it checks all the codes in your project.

EOF;
    }

    /**
     * 执行任务
     * @param array $arguments
     */
    protected function execute($arguments = array())
    {
        $path = ROOT_PATH;
        $finder = new TMFinder($path);
        $finder->addIgnoreFolders(array('.', '..', '.svn'));
        $finder->setFormat('/(\.php)$/');

        $findFiles = $finder->execute();
        $messages = array();

        foreach ($findFiles as $file)
        {
            $this->findQuery($file);
        }

        $this->checkSqlLog();

        $this->log($this->errorMessages);
    }

    /**
     * 查找调用了query方法的代码
     * @param string $file
     */
    protected function findQuery($file)
    {
        $content = file_get_contents($file);
        $contentArray = preg_split('/\n/', $content);

        $lineNum = 0;
        foreach ($contentArray as $key => $row)
        {

            $lineNum++;
            if(preg_match("/->query\(/", $row, $matches)){
                $this->keepMessage($file, "you should check whether the query method is ok, why not try select or update", $lineNum);
            }
        }
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
            $this->errorMessages[] = "[WARNING] ".realpath($file).': on line '.$line.' : '.$message."\n";
        }
    }

    /**
     * 检查开发环境的sql语句
     */
    protected function checkSqlLog()
    {
        if(!is_file(ROOT_PATH."log/devsql.log"))
        {
            $this->errorMessages[] = "You have not run the application, so the dev sql log is empty\n";
            return;
        }
        $content = file_get_contents(ROOT_PATH."log/devsql.log");

        $contentArray = preg_split('/\n/', $content);

        $handledSqlArray = array();
        $handledFilePathArray = array();

        foreach($contentArray as $key => $row)
        {
            $parseArray = $this->parseSqlLog($row);
            $sql = $parseArray["sql"];
            $time = $parseArray["time"];
            $filePath = $parseArray["filePath"];
            $line = $parseArray["line"];

            $tmpSql = "";
            if(preg_match("/^update (.+) set.+(where.+)$/i", $sql, $matches))
            {
                $table = $matches[1];
                $whereStr = $matches[2];
                $tmpSql = "explain select * from $table $whereStr";
            }else if(preg_match("/^select/",$sql)){
                $tmpSql = "explain ".$sql;
            }

            if($time > filemtime($filePath)){
                if(!empty($tmpSql) && !in_array($filePath."_".$line, $handledFilePathArray)){
                    $service = new TMService();
                    $result = $service->query($tmpSql, MYSQLI_ASSOC);

                    $item = $result[0];
                    if(strpos($item["Extra"], "Using filesort") !== FALSE
                    || strpos($item["Extra"], "Using temporary") !== FALSE
                    || (strpos($item["Extra"], "Using where") !== FALSE
                    && ($item["type"] == "ALL" or $item["type"] == "index"))){
                        $handledSqlArray[$sql]["status"] = self::NO_GOOD_SQL;
                    }else{
                        $handledSqlArray[$sql]["status"] = self::GOOD_SQL;
                    }

                    $handledFilePathArray[] = $filePath."_".$line;

                    if($handledSqlArray[$sql]["status"] == self::NO_GOOD_SQL)
                    {
                        $this->errorMessages[] = "[ERROR] ".$filePath." on line ".$line." : ".$sql." need to be optimized or add index to some tables"."\n";
                    }
                }
            }
        }
    }

    /**
     * 解析sql语句的日志
     * @param string $row
     * @return array $result
     */
    protected function parseSqlLog($row)
    {
        if(preg_match("/\[Time: (.+)\]\[File: (.+)\]\[Line: (.+)\](.+)/", $row, $matches))
        {
            $time = $matches[1];
            $filePath = $matches[2];
            $line = $matches[3];
            $sql = $matches[4];

            return array(
                "time" => $time,
                "filePath" => $filePath,
                "line" => $line,
                "sql" => $sql
            );
        }

        return array();
    }
}
?>
