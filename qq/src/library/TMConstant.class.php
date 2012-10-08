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
 * TMConstant
 * The constant class for util class
 *
 * @package config
 * @author  qrangechen <qrangechen@tencent.com>
 * @version TMConstant.class.php 2008-9-11 by ianzhang
 */
class TMConstant {
    //-----------------------CUSTOM CONSTANT------------------------------//
    

    //-----------------LIB CONSTANT------------------------------------//
    static $_uploadTypes = array ('.gif' => 'GRAPH', '.jpg' => 'GRAPH', '.png' => 'GRAPH', '.psd' => 'GRAPH', '.bmp' => 'GRAPH', '.tiff' => 'GRAPH', '.wbmp' => 'GRAPH',

    '.avi' => 'VIDEO', '.swf' => 'VIDEO', '.mpg' => 'VIDEO', '.mgeg' => 'VIDEO', '.wmv' => 'VIDEO', '.rm' => 'VIDEO', '.rmvb' => 'VIDEO', '.flv' => 'VIDEO',

    '.mp3' => 'AUDIO', '.wma' => 'AUDIO' );
    public static function uploadType($mixed) {
        return self::$_uploadTypes [$mixed];
    }

    public static function uploadTypes() {
        return self::$_uploadTypes;
    }
    
    //----------------ERROR CONSTANT---------------------------//
    //上传图片错误
    //上传图片错误
    const UPLOAD_ERROR_SYSTEM = 1;
    const UPLOAD_ERROR_WATER = 2;
    const UPLOAD_ERROR_THUMB = 3;
    const UPLOAD_ERROR_PIX = 4;
    const UPLOAD_ERROR_SIZE = 5;
    const UPLOAD_ERROR_COUNT = 6;
    const UPLOAD_ERROR_ONE_DAY = 7;
    const UPLOAD_ERROR_HEIGHT_WIDTH = 8;
    
    static $_uploadErrors = array (
        1 => "系统繁忙请稍后重新上传", 
        2 => "添加水印图失败，请重试", 
        3 => "生成缩略图失败，请重新上传", 
        4 => "您上传的文件格式不正确", 
        5 => "您上传的文件过大", 
        6 => "您上传的文件个数不符合要求", 
        7 => "您今天上传的文件个数超过了要求",
        8 => "您上传的图片尺寸不符合要求" 
    );
        
    public static function uploadError($mixed) {
        return self::$_uploadErrors [$mixed];
    }
    
    public static function uploadErrors() {
        return self::$_uploadErrors;
    }
        
    //-----------------------------------------------------------------//
}

?>