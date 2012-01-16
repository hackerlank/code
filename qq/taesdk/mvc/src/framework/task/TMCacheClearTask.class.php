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
 * TMCacheClearTask
 * 清楚缓存的任务
 *
 * @package sdk.mvc.src.framework.task
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMCacheClearTask.class.php 2011-4-20 by ianzhang
 */
class TMCacheClearTask extends TMCommandApplicationTask {
    protected $config = null;

    /**
     * @see TMTask
     */
    protected function configure() {
        $this->aliases = array ('cc');
        $this->namespace = 'cache';
        $this->name = 'clear';
        $this->briefDescription = 'Clears the cache';

        $this->detailedDescription = <<<EOF
The [cache:clear|INFO] task clears the project cache.

  [./taesdk.php cc|INFO]
it removes the cache for all available types, all applications,
and all environments.

EOF;
    }

    /**
     * @see TMTask
     */
    protected function execute($arguments = array()) {
        $cacheDir = ROOT_PATH."cache";

        $this->clearDir($cacheDir);
    }

    /**
     * 清除文件夹
     * @param string $cacheDir
     */
    protected function clearDir($cacheDir)
    {
        $handle = opendir($cacheDir); //打开当前目录

        //循环读取文件
        while (false !== ($file = readdir($handle)))
        {
            if($file == "." || $file == "..")
            {
                continue;
            }
            $fileFrom = $cacheDir . DIRECTORY_SEPARATOR .$file;

            if (is_dir($fileFrom))
            {
                $this->clearDir($fileFrom);
                $this->log($fileFrom, 'dir-');
                rmdir($fileFrom);
            }else{
                $this->log($fileFrom, is_link($fileFrom) ? 'link-' : 'file-');
                unlink($fileFrom);
            }
        }
        closedir($handle);
    }
}
?>