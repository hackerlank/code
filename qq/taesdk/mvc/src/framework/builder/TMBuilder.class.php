<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2009 BY  TENCENT  CORPORATION.  ALL RIGHTS
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
 * 自动化生成项目builder类
 *
 * @package sdk.mvc.src.framework.builder
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMBuilder.class.php 2009-10-30 by ianzhang
 */
class TMBuilder {
    /**
     * Build folder
     *
     * @access public
     * @param  string $name     the folder name
     * @param string $path       the folder's parent's path
     * @return string $resultPath    the folder's path in os 返回完整的文件夹路径
     */
    public static function buildFolder($name, $path = ROOT_PATH)
    {
        if(file_exists($path.$name))
        {
            throw new TMBuilderException("dir exists");
        }
        $current_umask = umask(0000);
        mkdir($path.$name,0777,true);
        umask($current_umask);

        return $path.$name;
    }

    /**
     * Build file
     *
     * @access public
     * @param  string $filePath     the file path
     * @param  string $templatePath   the file template path
     * @param  array $extract      the map for variables replacement 用于变量替换的键值对
     * @return void
     */
    public static function buildFile($filePath, $templatePath, $extract = array())
    {
        TMFileTool::generateFile($templatePath, $filePath, $extract);
    }

    /**
     * copy files and directories recursively 递归复制目录下面的文件和文件夹
     *
     * @access public
     * @param  string $dirFrom     the source dir path复制文件夹源路径
     * @param  string $dirTo   the destination dir path 新文件夹路径
     * @param  array $ignore    the array include files need ignore 需要忽略的文件名数组
     * @return  void
     */
    public static function copyDir($dirFrom,$dirTo, $ignore = array())
    {
        if (is_file($dirTo))
        {
            die("无法建立目录 $dirTo");
        }

        if (!file_exists($dirTo))
        {
            mkdir($dirTo);
        }

        $handle = opendir($dirFrom); //打开当前目录

        //循环读取文件
        while (false !== ($file = readdir($handle)))
        {
            if(in_array($file,$ignore))
            {
                continue;
            }
            if($file == "." || $file == "..")
            {
                continue;
            }
            $fileFrom = $dirFrom . DIRECTORY_SEPARATOR .$file;
            $fileTo = $dirTo .DIRECTORY_SEPARATOR .$file;

            if (is_dir($fileFrom))
            {
                self::copyDir($fileFrom,$fileTo,$ignore);
            } else {
                copy($fileFrom,$fileTo);
            }
        }

        closedir($handle);
    }

}
