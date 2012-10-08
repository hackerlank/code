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
 * TMExchangeUtils
 * 兑换工具类
 *
 * @package sdk.mvc.src.framework.util
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMExchangeUtils.class.php 2010-12-30 by ianzhang    
 */
class TMExchangeUtils {
    /**
     * getExchangeTimes
     * 用户提交兑换码错误连续达到一定数量，封停用户在规则时间内不能再进行兑换
     *
     * @param  string $userkey     缓存中每个QQ对应的键值
     * @param  int    $times   最多可连续失败次数
     * @param  int    $expire  封停的时间
     * @param  bool   $flag    兑换码是否有效
     *
     * @return array
     */
    public static function getExchangeTimes($userkey,$times,$expire,$flag){
        $memCache = TMMemCacheMgr::getInstance();
        $exchangeTimes = array();
        $key = TMConfig::get("tams_id")."_".$userkey."_ExchangeTimes on ".date("Y-m-d");
        //$memCache->clear($key,false);
        $value = $memCache->get($key);
        $exchangeTimes["status"] = false;
        //如果超过5次，封停用一天,返回失败
        if($value && $value>($times-1))
        {
            $exchangeTimes["times"] = $value;
            $exchangeTimes["remain"] = 0;
            return  $exchangeTimes;
        }
        if($flag)
        {
            //有效兑换码则清除cache值
            if($value)
            {
                TMDebugUtils::debugLog('memcache clear: '.$key);
                $memCache->clear($key,false);
            }
            $exchangeTimes["status"] = true;
            $exchangeTimes["times"] = 0;
            $exchangeTimes["remain"] = $times;
        } else {
            //无效兑换码则累加cache值
            if($value){
                $value++;
//              TMDebugUtils::debugLog('memcache set '.$key.' to '.$value);
                $memCache->set($key,$value,$expire);
                $exchangeTimes["times"] = $value;
                $exchangeTimes["remain"] = $times - $value;
            } else {
                //TMDebugUtils::debugLog('memcache set '.$key.' to 1');
                $memCache->set($key,1,$expire);
                $exchangeTimes["times"] = 1;
                $exchangeTimes["remain"] = $times - 1;
            }
        }
        return  $exchangeTimes;
    }
}
?>