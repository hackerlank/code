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
 * TMUploader
 * The class of uploading file
 *
 * @package sdk.mvc.src.framework.upload
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMUploader.class.php 2008-10-20 by ianzhang
 */
class TMUploader {
  /**
   * createObject
   * Create upload file object
   *
   * @param  array $file        the request file array
   * @param  string $type       the request file type
   * @param  array $config      the config
   * @return TMUploadFile
   */
    public static function createObject($file,$type,$config=array()){
        if (empty($type)) {
            $suffix = TMUtil::getSuffix($file["name"]);
            $type = TMConstant::uploadType($suffix);
        }
        switch($type){
            case "GRAPH":
                if ($file['error'] != UPLOAD_ERR_NO_FILE) {
                    return new TMUploadGraph($file, $config);
                }
            case "VIDEO":
                if ($file['error'] != UPLOAD_ERR_NO_FILE) {
                    return new TMUploadVideo($file, $config);
                }
            case "AUDIO":
                if ($file['error'] != UPLOAD_ERR_NO_FILE) {
                    return new TMUploadAudio($file, $config);
                }
        }
    }
}