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
 * TMTaeInitFilter
 * 处理全站TAE使用包的提前设置
 *
 * @package sdk.mvc.src.framework.filter
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMTaeInitFilter.class.php 2010-12-30 by ianzhang
 */
class TMTaeInitFilter extends TMFilter {
    
    /**
     * Filter的执行方法
     * @param TMFilterChain $filterChain
     */
    public function execute($filterChain) {
        self::taeInit();
        $filterChain->execute();
    }
    
    /**
     * 进行tae的初始化操作
     */
    public static function taeInit()
    {
        $taeServers = TMConfig::get("tae", "server");
        TaeCore::taeInit(TaeConstants::SERVER_LIST,$taeServers);
        TaeCore::taeInit(TaeConstants::USER_IP,TMUtil::getClientIp());
        TaeCore::taeInit(TaeConstants::VERSION,"1.1");
        TaeCore::taeInit(TaeConstants::ACT_ID, TMConfig::get("tams_id"));
        
        if(TMAuthUtils::isLogin()){
            TaeCore::taeInit(TaeConstants::UIN,strval(TMAuthUtils::getUin()));
        }else{
            TaeCore::taeInit(TaeConstants::UIN,'0');
        }
    }
}

?>