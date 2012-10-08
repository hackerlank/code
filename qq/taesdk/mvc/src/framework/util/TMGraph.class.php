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
 * TMGraph
 * 图像操作类
 *
 * @package sdk.mvc.src.framework.util
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMGraph.class.php 2010-12-27 by ianzhang    
 */
class TMGraph extends TMLibGraph{
    /**
     * 构造函数
     * @return void
     */
    public function  __construct() {
        parent::__construct();
    }

     /**
     * 生成缩略图
     *
     * @param string $srcFile      the src file path
     * @param int $width           the Thumbnails width
     * @param int $height          the Thumbnails height
     */
   public static function makeThumb($srcFile, $width, $height, $isStretch = true, $inScale=true) {
        $thumbPrefix = TMConfig::get("upload", "thumb_pix");
        return parent::makeThumb($srcFile, $width, $height, $thumbPrefix, $isStretch, $inScale);
    }
}
?>