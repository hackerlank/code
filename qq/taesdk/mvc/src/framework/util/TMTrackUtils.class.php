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
 * TMTrackUtils
 * 行为监测客户端工具类
 *
 * @package sdk.mvc.src.framework.util
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMTrackUtils.class.php 2010-12-27 by ianzhang
 */
class TMTrackUtils extends TMLibTrackUtils {
    /**
     * 发送行为监测请求
     * @param string $qq
     * @param int $actionId 行为id
     * @param int $campaignId 活动id
     * @param string $reserve 备用字段
     * @return mixed
     */
    public static function trackAction($qq, $actionId, $campaignId = null, $reserve = "") {
        if(empty($campaignId)){
            $campaignId = TMConfig::get("tams_id");
        }
        TMDebuggerManager::add("actionTrack", array($qq, $actionId, $campaignId, $reserve));
        return parent::trackAction($qq, $actionId, $campaignId
            ,TMConfig::get('track', 'url'), TMConfig::get('track', 'host_ip'), $reserve);
    }
}
?>