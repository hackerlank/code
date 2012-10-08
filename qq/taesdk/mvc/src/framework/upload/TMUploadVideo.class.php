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
 * The upload video
 *
 * @package sdk.mvc.src.framework.upload
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMUploadVideo.class.php 2008-10-20 by ianzhang
 */
class TMUploadVideo extends TMUploadFile
{
    /**
     * 构造函数
     * @param array $file
     * @param array $config
     * @return void
     */
    public function __construct($file, $config=array())
    {
        parent::__construct($file, $config);

        $this->configSize = "VIDEO_MAX_SIZE";
        $this->configTypes = "video";
    }

    /**
     * upload file
     *
     * @param  string $filename       the file name
     * @param  string $fileMode       the file mode
     * @param  bool $create          create directory or not
     * @param  string $dirMode       the file directory mode
     * @return string this new file name
     * @throw TMUploadException
     */
    public function upload($filename, $fileMode = 0644, $create = true, $dirMode = 0755)
    {
        return parent::upload($filename, $fileMode, $create, $dirMode);
    }

    /**
     * Validate the single upload operation
     * @throws TMUploadException
     */
    public function validate()
    {
        parent::validate();
    }

    /**
     * Handle after upload
     *
     * @param  string $filename       the file name
     * @param  array $methodArray     the array of method
     * @return string this file type
     * @throws TMUploadException
     */
    public function handle($filename, array $methodArray = array())
    {
        return parent::handle($filename, $methodArray);
    }
}