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
 * The upload file object
 *
 * @package sdk.mvc.src.framework.upload
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMUploadFile.class.php 2008-10-20 by ianzhang
 */
abstract class TMUploadFile implements TMUploadInterface
{
    protected $size;
    protected $path;
    protected $name;
    protected $type;
    protected $error;

    protected $configSize;
    protected $configTypes;

    protected $config;

    /**
     * __construct
     * The construct function
     *
     * @param array $file          the request file array
     * @throws TMUploadException
     */
    public function __construct($file, $config=array())
    {
        if (!empty($config)) {
            $this->config = $config;
        }
        
        $configArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/upload.yml");
        TMConfig::set($configArray);
        
        switch($file['error'])
        {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errCode = $this->getConfig('UPLOAD_ERROR_SIZE');
                $message = $this->getErrorMessage($errCode);
                $adlog = new TMLog();
                $adlog->ll($message);
                throw new TMUploadException($message);
            default:
                $this->error = $file ['error'];
                $this->size = $file ['size'];
                $this->path = $file ['tmp_name'];
                $this->name = $file ['name'];
                $this->type = $file ['type'];
        }
    }

    /**
     * 获取配置
     * 先判断当前对象是否设置了配置数组，如果设置了则取当前对象的配置，否则到TMConstant或者TMConfig中获取
     *
     * @param string $name
     * @param string $file 配置所在类文件，TMConstant、TMConfig之一
     * @return mixed 配置值
     */
    public function getConfig($name, $file="TMConstant")
    {
        if (!empty($this->config))
        {
            return $this->config[$name];
        }
        else
        {
            return @constant("$file::$name");
        }
    }

    /**
     * 获取错误信息
     * 先判断当前对象是否设置了配置数组，如果设置了则取当前对象的配置中的错误信息，否则到TMConstant::uploadError获取
     *
     * @param int $code 错误代码
     * @return string 错误信息
     */
    public function getErrorMessage($code)
    {
        if (!empty($this->config))
        {
            return $this->config['errors'][$code];
        }
        else
        {
            return TMConstant::uploadError($code);
        }
    }

    /**
     * 获取系统所允许上传的文件后缀名
     * 先判断当前对象是否设置了配置数组，如果设置了则取当前对象的配置中的文件后缀名，否则通过TMConfig::graphValidatedTypes()获取
     *
     * @param $type 上传类型, 默认是graph
     * @return array 允许的文件后缀名
     */
    public function getValidatedTypes($type="graph") {
        if (!empty($this->config)) {
            return $this->config['validatedTypes'];
        } else {
            if ($type == "graph") {
                return TMConfig::get("upload","graphValidatedTypes");
            } else if ($type=="audio") {
                return TMConfig::get("upload","audioValidatedTypes");
            } else if ($type == "video") {
                return TMConfig::get("upload","videoValidatedTypes");
            }
        }
    }

    /**
     * getFileName
     * Retrieves a file name.
     *
     *
     * @return string A file name, if the file exists, otherwise null
     */
    public function getFileName() {
        return $this->name;
    }

    /**
     * getFilePath
     * Retrieves a file path.
     *
     *
     * @return string A file path, if the file exists, otherwise null
     */
    public function getFilePath() {
        return $this->path;
    }

    /**
     * getFileSize
     * Retrieve a file size.
     *
     *
     * @return int A file size, if the file exists, otherwise null
     */
    public function getFileSize() {
        return $this->size;
    }

    /**
     * getFileType
     * Retrieves a file type.
     *
     * This may not be accurate. This is the mime-type sent by the browser
     * during the upload.
     *
     *
     * @return string A file type, if the file exists, otherwise null
     */
    public function getFileType() {
        return $this->type;
    }

    /**
     * getFileError
     * Retrieves a file error.
     *
     *
     * @return int One of the following error codes:
     *
     *             - <b>UPLOAD_ERR_OK</b>        (no error)
     *             - <b>UPLOAD_ERR_INI_SIZE</b>  (the uploaded file exceeds the
     *                                           upload_max_filesize directive
     *                                           in php.ini)
     *             - <b>UPLOAD_ERR_FORM_SIZE</b> (the uploaded file exceeds the
     *                                           MAX_FILE_SIZE directive that
     *                                           was specified in the HTML form)
     *             - <b>UPLOAD_ERR_PARTIAL</b>   (the uploaded file was only
     *                                           partially uploaded)
     *             - <b>UPLOAD_ERR_NO_FILE</b>   (no file was uploaded)
     */
    public function getFileError() {
        return $this->error;
    }

    /**
     * saveUploadFile
     * Saves an uploaded file.
     * use @ don't show error info and use our error info
     *
     * @param string $file      An absolute filesystem path to where you would like the
     *                          file moved. This includes the new filename as well, since
     *                          uploaded files are stored with random names
     * @param int    $fileMode  The mode to use for the new file
     * @param bool   $create    Indicates that we should make the directory before moving the file
     * @param int    $dirMode   The mode to use when creating the directory
     *
     * @return bool true, if the file was moved, otherwise false
     *
     * @throws TMUploadException If a major error occurs while attempting to move the file
     */
    protected function saveUploadFile($newfile, $fileMode = 0644, $create = true, $dirMode = 0755) {
        $filename = $this->path;
        // get our directory path from the destination filename
        $directory = dirname ( $newfile );
        if (! is_readable ( $directory )) {
            if ($create && ! @mkdir ($directory, $dirMode, true)) {
                // failed to create the directory
                $adlog = new TMLog ();
                $adlog->ll ( 'Failed to create file upload directory ' . $directory . '.' );
                throw new TMUploadException ('Failed to create file upload directory ' . $directory . '.');
            }
            @chmod ($directory, $dirMode );
        }
        if (is_dir ( $directory )) {
            if (is_writable ( $directory )) {
                if (@move_uploaded_file ( $filename, $newfile )) {
                    // chmod our file
                    @chmod ( $newfile, $fileMode );
                    return $newfile;
                }
            }
        }
        $adlog = new TMLog ( );
        $adlog->ll ( "Handle upload file Error" );
        $errCode = $this->getConfig("UPLOAD_ERROR_SYSTEM");
        $errMessage = $this->getErrorMessage($errCode);
        throw new TMUploadException($errMessage);
    }

    /**
     * upload
     * Move the tmp upload file to the disk file
     *
     * @see lib.upload.TMUploadInterface
     */
    public function upload($filename, $fileMode = 0644, $create = true, $dirMode = 0755) {
        $pix = TMUtil::getSuffix ( $this->name );
        return $this->saveUploadFile ( $filename . $pix, $fileMode, $create, $dirMode );
    }

    /**
     * validate
     * Validate the single upload operation
     *
     * @see lib.upload.TMUploadInterface
     * @throws TMUploadException
     */
    public function validate() {
        $error = $this->error;
        switch ($error) {
            case UPLOAD_ERR_PARTIAL :
            case UPLOAD_ERR_NO_TMP_DIR :
            case UPLOAD_ERR_CANT_WRITE :
                $errCode = $this->getConfig("UPLOAD_ERROR_SYSTEM");
                $errMessage = $this->getErrorMessage($errCode);
                throw new TMUploadException($errMessage);
        }

        $maxSize = (int) TMConfig::get("upload", "file_max_size");
        if ($this->size > $maxSize * 1024) {
            $errCode = $this->getConfig("UPLOAD_ERROR_SIZE");
            $errMessage = $this->getErrorMessage($errCode);
            throw new TMUploadException($errMessage);
        }

        $pix = TMUtil::getSuffix($this->name);
        if (!in_array($pix, $this->getValidatedTypes($this->configTypes))) {
            $errCode = $this->getConfig("UPLOAD_ERROR_PIX");
            $errMessage = $this->getErrorMessage($errCode);
            throw new TMUploadException($errMessage);
        }
    }

    /**
     * handle
     * Handle the upload file (example: compress, water mark, thumb and so on)
     *
     * @see lib.upload.TMUploadInterface
     */
    public function handle($filename, array $methodArray = array()) {
        $pix = TMUtil::getSuffix ( $this->name );
        return $pix;
    }
}
