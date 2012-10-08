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
 * The upload file service
 *
 * Usage
 * <code>
 * upload($fileArray, "aaa.jpg", array("thumb", "water" => array("pathname" => "water.png")))
 * 同时执行压缩图和水印
 * </code>
 *
 * @package sdk.mvc.src.framework.service
 * @author  ianzhang <ianzhang@tencent.com>
 * @version UploadService.class.php 2008-10-9 by ianzhang
 */
class UploadService
{
    /**
     * Initialize the upload files array
     *
     * @param  TMWebRequest $request       the http web request
     * @param  string $name                the request file name
     * @param  string $type                for example "GRAPH","AUDIO","VIDEO"
     * @param  array $config               config array
     * @return array $array                the upload files array
     */
    public function initialize($request,$name,$type,$config=array())
    {
        $uploadArray = $_FILES[$name];
        if(empty($uploadArray))
        {
            return array();
        }
        $files = array();
        if (is_array($uploadArray['error']))
        {
            foreach($uploadArray['error'] as $key => $error)
            {
                $file = array();
                $file['error'] = $uploadArray['error'][$key];
                $file['size'] = $uploadArray['size'][$key];
                $file['name'] = $uploadArray['name'][$key];
                $file['type'] = $uploadArray['type'][$key];
                $file['tmp_name'] = $uploadArray['tmp_name'][$key];
                $files[] = $file;
            }
        }
        else
        {
            $file = array();
            $file['error'] = $uploadArray['error'];
            $file['size'] = $uploadArray['size'];
            $file['name'] = $uploadArray['name'];
            $file['type'] = $uploadArray['type'];
            $file['tmp_name'] = $uploadArray['tmp_name'];
            $files[] = $file;
        }
        $array = array();

        foreach($files as $file)
        {
            $object = TMUploader::createObject($file,$type, $config);
            if ($object != null)
            {
                $array[] = $object;
            }
        }
        return $array;
    }

    /**
     * Validate the upload files' count
     *
     * @param  array $array              the upload files array
     * @param  integer $minCount         the upload files minimal count
     * @param  integer $maxCount         the upload files maximal count
     * @param  array $config             config array
     * @throw TMUploadExeption
     */
    public function validateCount(array $array, $minCount, $maxCount, $config=array())
    {
        if (count($array) < $minCount || count($array) > $maxCount)
        {
            if (!empty($config))
            {
                $errCode = $config['UPLOAD_ERROR_COUNT'];
                $errMessage = $config['errors'][$errCode];
            }
            else
            {
                $errMessage = TMConstant::uploadError(TMConstant::UPLOAD_ERROR_COUNT);
            }
            throw new TMUploadException($errMessage);
        }
    }

    /**
     * Validate one day upload count
     *
     * @param  string|integer $qq        QQ
     * @param  string $column            the column saving upload user's qq
     * @param string $tbl                table name
     * @param  array $config             config array
     * @throw TMUploadExeption
     */
    public function validateOneDayCount($qq, $column='FQQ', $tbl='Tbl_File', $config=array())
    {
        $service = new TMService();
        $fields = array();
        $fields[$column] = $qq;
        if (!empty($config))
        {
            $oneday = (int) $config['UPLOAD_ONE_DAY'];
            if ($service->getCountOneDay($fields, $tbl) >= $oneday)
            {
                $errCode = $config['UPLOAD_ERROR_ONE_DAY'];
                $errMessage = $config['errors'][$errCode];
                throw new TMUploadException($errMessage);
            }
        }
        else
        {
            if ($service->getCountOneDay($fields, $tbl) >= TMconfig::get("upload", "upload_one_day"))
            {
                throw new TMUploadException(TMConstant::uploadError(TMConstant::UPLOAD_ERROR_ONE_DAY));
            }
        }
    }

    /**
     * Validate multiple files using the single file's validate method
     *
     * @param  array $array         the upload files array
     *
     * @throw TMUploadException
     */
    public function validate(array $array)
    {
        foreach($array as $element)
        {
            $element->validate();
        }
    }

    /**
     * Save the upload files and handle the files
     *
     * @param  array $array          the upload files array
     * @param  string $fileName      the saved file's name
     * @param  array $methodArray    the handle method array
     * @param  string $path          the upload path
     *
     * @return array $fileNameArray
     */
    public function upload(array $array, $fileName, $methodArray=array(), $path='')
    {
        if (empty($path))
        {
            $path = TMConfig::get("upload", "upload_path");
        }
        $fileNameArray = array();
        foreach($array as $key => $element)
        {
            $filename = $element->upload($path.$fileName."_".($key+1));
            $pix = $element->handle($filename, $methodArray);
            $fileNameArray[] = $fileName."_".($key+1).$pix;
        }
        return $fileNameArray;
    }

}