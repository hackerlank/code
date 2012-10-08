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
 * TMFinder
 * 查找文件类
 *
 * @package sdk.mvc.src.framework.util
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMFinder.class.php 2011-4-22 by ianzhang    
 */
class TMFinder
{
    protected $roots = array();
    protected $format = '';
    protected $ignoreFiles = array();
    protected $ignoreFolders = array();
    protected $returnFiles = array();
    protected $config = array();

    /**
     * 构造函数
     * @param array $roots
     */
    public function __construct($roots = array())
    {
        if(!is_array($roots))
        {
            $roots = array($roots);
        }
        $this->roots = $roots;
    }
    
    /**
     * 设置扫描根目录
     * @param array $dirs
     */
    public function setRoots($dirs)
    {
        $this->roots = (array) $dirs;
    }

    /**
     * 增加扫描路径
     * @param mixed $dirs
     */
    public function addRoots($dirs)
    {
        if(!is_array($dirs))
        {
            $dirs = array($dirs);
        }
        
        $this->roots = array_merge($this->roots, $dirs);
    }
    
    /**
     * 设置扫描文件匹配条件
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * 设置忽略文件夹
     * @param mixed $ignoreFolders
     */
    public function addIgnoreFolders($ignoreFolders)
    {
        if(!is_array($ignoreFolders))
        {
            $ignoreFolders = array($ignoreFolders);
        }
        
        $this->ignoreFolders = array_merge($this->ignoreFolders, $ignoreFolders);
    }

    /**
     * 获得忽略文件夹
     * @return array
     */
    public function getIgnoreFolders()
    {
        return $this->ignoreFolders;
    }

    /**
     * 设置忽略文件
     * @param mixed $ignoreFiles
     */
    public function addIgnoreFiles($ignoreFiles)
    {
        if(!is_array($ignoreFiles))
        {
            $ignoreFiles = array($ignoreFiles);
        }
        
        $this->ignoreFiles = array_merge($this->ignoreFiles, $ignoreFiles);
    }
    
    /**
     * 返回忽略的文件
     * @return array
     */
    public function getIgnoreFiles()
    {
        return $this->ignoreFiles;
    }

    /**
     * 查找文件
     * @return array
     */
    public function execute()
    {
        if (!$this->roots)
        {
            return array();
        }

        foreach ($this->roots as $root)
        {
            $root = rtrim($root, DIRECTORY_SEPARATOR);
            $this->opendir($root);
        }

        return $this->returnFiles;
    }

    /**
     * 查找文件的递归操作
     * @param string $dir
     */
    private function opendir($dir)
    {
        if (!is_dir($dir))
        {
            return;
        }

        $handle = opendir($dir);
        while (false !== ($file = readdir($handle)))
        {
            if (!in_array($file, $this->ignoreFolders))
            {
                if (is_file($dir.DIRECTORY_SEPARATOR.$file))
                {
                    if (!in_array($file, $this->ignoreFiles) && preg_match($this->format, $file))
                    {
                        $this->returnFiles[] = $dir.DIRECTORY_SEPARATOR.$file;
                    }
                }
                else
                {
                    $this->opendir($dir.DIRECTORY_SEPARATOR.$file);
                }
            }
        }
    }
}
