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
 * File Cache class, this class is used for yaml cache in TMYamlCacher
 *
 * @package sdk.lib3.src.base.core
 * @author  Samon Ma <samonma@tencent.com>
 */
class TMFileCache
{
    /**
     * This class is singleton
     */
    private static $instance;

    /**
     * get a instance of TMFileCache,create one if not exist
     * @access public
     * @return TMFileCache
     */
    public static function getInstance()
    {
        if(self::$instance == null)
        {
            $class = __CLASS__;
            self::$instance = new $class();
        }

        return self::$instance;
    }

    /**
     * create file autoload.php
     * @access public
     * @return void
     */
    public function commit()
    {
        if (!is_dir(CACHE_PATH.$this->path))
        {
            $oldumask = umask(0);
            mkdir(CACHE_PATH.$this->path, 0777, true);
            umask($oldumask);
        }
        file_put_contents(CACHE_PATH.$this->path.$this->name, $this->content);
        chmod(CACHE_PATH.$this->path.$this->name, 0777);
    }

    /**
     * set properties and call commit abstract function
     *
     * @access public
     * @param string $filePath  the file path
     * @param string $fileName      the file name
     * @param string $fileContent   the file content
     * @return void
     */
    public function execute($filePath, $fileName, $fileContent)
    {
        $this->setPath($filePath);
        $this->setName($fileName);
        $this->setContent($fileContent);

        $this->commit();
    }

    /**
     * set file path
     *
     * @access protected
     * @param string $path the file path
     * @return void
     */
    protected function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * set file name
     *
     * @access protected
     * @param string $name the file name
     * @return void
     */
    protected function setName($name)
    {
        $this->name = $name;
    }

    /**
     * set file content
     *
     * @access protected
     * @param string $content the file content
     * @return void
     */
    protected function setContent($content)
    {
        $this->content = $content;
    }
}
