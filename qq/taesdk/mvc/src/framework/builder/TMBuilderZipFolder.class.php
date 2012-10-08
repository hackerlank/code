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
 * 处理自动化生成项目中使用的zip文件夹类
 *
 * @package sdk.mvc.src.framework.builder
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMBuilderZipFolder.class.php 2009-10-30 by ianzhang
 */
class TMBuilderZipFolder {
    protected $zip;
    protected $root;
    protected $ignored_names;

    /**
     * 构造函数
     * @param string $file
     * @param string $folder
     * @param array $ignored
     * @return void
     */
    public function __construct($file, $folder, $ignored=null) {
        $this->zip = new ZipArchive();
        $this->ignored_names = is_array($ignored) ? $ignored : $ignored ? array($ignored) : array();
        if ($this->zip->open($file, ZIPARCHIVE::CREATE)!==TRUE) {
            throw new Exception("cannot open <$file>\n");
        }
        $folder = substr($folder, -1) == '/' ? substr($folder, 0, strlen($folder)-1) : $folder;
        if(strstr($folder, '/')) {
            $this->root = substr($folder, 0, strrpos($folder, '/')+1);
            $folder = substr($folder, strrpos($folder, '/')+1);
        }
        $this->zip($folder);
        $this->zip->close();
    }

    /**
     * 进行代码打包
     * @param string $folder
     * @param string $parent
     * @return void
     */
    protected function zip($folder, $parent=null) {
        $full_path = $this->root.$parent.$folder;
        $zip_path = $parent.$folder;
        $this->zip->addEmptyDir($zip_path);
        $dir = new DirectoryIterator($full_path);
        foreach($dir as $file) {
            if(!$file->isDot()) {
                $filename = $file->getFilename();
                if(!in_array($filename, $this->ignored_names)) {
                    if($file->isDir()) {
                        $this->zip($filename, $zip_path.'/');
                    }
                    else {
                        $this->zip->addFile($full_path.'/'.$filename, $zip_path.'/'.$filename);
                    }
                }
            }
        }
    }
}