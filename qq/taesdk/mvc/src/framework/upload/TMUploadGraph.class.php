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
 * The upload graph
 *
 * Usage
 * <code>
 * handle("aaa.jpg", array("thumb", "water" => array("pathname" => "water.png")))
 * 同时执行压缩图和水印
 * </code>
 *
 *
 * @package sdk.mvc.src.framework.upload
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMUploadGraph.class.php 2008-10-20 by ianzhang
 */
class TMUploadGraph extends TMUploadFile
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

        $this->configSize = "FILE_MAX_SIZE";
        $this->configTypes = "graph";
    }

    /**
     * upload file
     *
     * @param  string $filename       the file name
     * @param  string $fileMode       the file mode
     * @param  bool $create          create directory or not
     * @param  string $dirMode       the file directory mode
     * @return string        this new file name
     * @throw TMUploadException
     */
    public function upload($filename, $fileMode = 0644, $create = true, $dirMode = 0755)
    {
        return parent::upload($filename, $fileMode, $create, $dirMode);
    }

    /**
     * Handle after upload
     *
     * @param  string $filename       the file name
     * @param  array $methodArray     the array of method
     * @return void
     */
    public function handle($filename, array $methodArray = array())
    {
        if(in_array('water',$methodArray))
        {
            $this->waterMark($filename, $methodArray["water"]["pathname"]);
        }
        if(in_array('thumb',$methodArray))
        {
            $this->makeThumb($filename);
        }
        return parent::handle($filename, $methodArray);
    }

    /**
     * Validate the single upload operation
     *
     */
    public function validate()
    {
        parent::validate();
    }

    /**
     * add  waterMark to picture
     *
     * @param  string $filename       the file name
     * @param  string $waterpath      the water path
     * @return void
     *
     * @throw TMUploadException
     */
    private function waterMark($filename, $waterpath='')
    {
        if (empty($waterpath))
        {
            $waterpath = TMConfig::get("upload", "water_path");
        }
        $array = array(
            'img_url'=> $filename,
            'mark_url' => $waterpath,
            'ref_point' => TMConfig::get("upload", "water_point"),
            'pos_x' => TMConfig::get("upload", "water_x_pos"),
            'pos_y' => TMConfig::get("upload", "water_y_pos")
             );
        $result = TMGraph::makeWatermark($array);
        if ($result['message'] != 'ok')
        {
            $adlog = new TMLog();
            $errCode = $this->getConfig("UPLOAD_ERROR_WATER");
            $errMessage = $this->getErrorMessage($errCode);
            $adlog->ll($errMessage);
            throw new TMUploadException($errMessage);
        }
    }

    /**
     * make thumb picture
     *
     * @param  string $filename       the file name
     * @return void
     *
     * @throw TMUploadException
     */
    private function makeThumb($filename)
    {
        $width = TMConfig::get("upload","thumb_width");
        $height = TMConfig::get("upload", "thumb_height");
        if (TMGraph::makeThumb($filename, $width, $height) != true)
        {
            $adlog = new TMLog();
            $errCode = $this->getConfig("UPLOAD_ERROR_THUMB");
            $errMessage = $this->getErrorMessage($errCode);
            $adlog->ll($errMessage);
            throw new TMUploadException($errMessage);
        }
    }
}