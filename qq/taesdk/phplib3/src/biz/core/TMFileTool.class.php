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
 * TMFileTool
 *
 * @package sdk.lib3.src.biz.core
 * @author  Samon Ma <samonma@tencent.com>
 */
class TMFileTool
{
    /**
     * you can use this method to generate a folders template
     *
     * @param array $parameter fromPath toPath value like generateFile method
     * @throws TMFileException
     */
    public static function generate($parameter)
    {
        // yes, no or exception
        if (isset($parameter['replace']))
        {
            if ('exception' === $parameter['replace'])
            {
                throw new TMFileException('File Exist: '.$parameter['toPath']);
            }
            else if(false === $parameter['replace'])
            {
                return;
            }
            else
            {
                // empty
            }
        }

        if (!isset($parameter['extracter']))
        {
            $parameter['extracter'] = array();
        }

        if (is_dir($parameter['fromPath']))
        {
            foreach (self::availableFile($parameter['fromPath']) as $file)
            {
                $newFileName = $file;

                self::generateFile($parameter['fromPath'].'/'.$file, $parameter['toPath'].'/'.$newFileName, $parameter['extracter']);
            }
        }
        else
        {
            self::generateFile($parameter['fromPath'], $parameter['toPath'], $parameter['extracter']);
        }
    }

    /**
     * generate a template file to a php file
     *
     * @param string $fromPath always the template path
     * @param string $toPath the generated file where you want to put
     * @param array $extracter will treate it like the extracter() function
     */
    public static function generateFile($fromPath, $toPath, array $extracter)
    {
        if ($extracter)
        {
            extract($extracter);
        }

        ob_start();
        include($fromPath);
        $classContent = ob_get_contents();
        ob_end_clean();

        $classContent = preg_replace(array('/\[\?php/', '/\?\]/'), array('<?php', '?>'), $classContent);
        $currentMask = umask(0000);

        if (!is_dir(dirname($toPath)))
        {
            mkdir(dirname($toPath), 0777, true);
        }

        file_put_contents($toPath, $classContent);
        chmod($toPath,0777);

        umask($currentMask);
    }
}