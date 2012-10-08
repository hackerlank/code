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
 * TMHtmlHelper
 * html字符串辅助输出类
 *
 * @package sdk.mvc.src.framework.helper
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMHtmlHelper.class.php 2010-12-27 by ianzhang    
 */
class TMHtmlHelper extends TMLibHtmlHelper {
    /**
     * 加入jump监测链接的跳转链接
     * @param string $url
     * @param string $qq
     * @param int $type
     * @param int $cpid
     * @return string
     */
    public static function urlJump($url, $qq = '', $type = 1, $cpid = null) {
        if(empty($cpid))
        {
            $cpid = TMConfig::get("tams_id");
        }
        return parent::urlJump($url, $qq, $type, $cpid);
    }
}
?>