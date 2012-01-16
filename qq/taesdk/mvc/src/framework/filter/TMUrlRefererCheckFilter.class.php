<?php

/*
 * ---------------------------------------------------------------------------
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
 * ---------------------------------------------------------------------------
 */

/**
 * referer访问检查
 *
 * 开关：config/filter.yml - TMUrlRefererCheckFilter
 * 配置：config/filter/url.referer.check.yml
 *
 * @package sdk.mvc.src.framework.filter
 * @author  gastonwu <gastonwu@tencent.com>
 */
class TMUrlRefererCheckFilter extends TMFilter {

    /**
     * referer访问检查
     *
     * @param TMFilterChain $filterChain
     */
    public function execute($filterChain) {
        $configArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/filter/url.referer.check.yml");
        
        $path =  $_SERVER['SCRIPT_URL'];
        $limitUrls = $configArray['CurrentSite']['LimitUrl'];
        $limitUrls = empty($limitUrls) ? array() : $limitUrls;
        //检查是否在要求检查的uri列表中
        if(in_array($path,$limitUrls) === false){
            $filterChain->execute();
			return;
        }
        //若是本域访问，则不做检查
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
        $refererTmpArray = parse_url($referer);
        $refererHost = $refererTmpArray['host'];
        $currentHost = $_SERVER['HTTP_HOST'];
        if($refererHost == $currentHost){
            $filterChain->execute();
			return;
        }

        //是否允许referer为空
        $AllowNoneRerere =  $configArray['Access']['AllowNoneRerere'];
        if($AllowNoneRerere && empty($referer)){
            $filterChain->execute();
			return;
        }
        //必须要检查的referer
        $RefererDomainWhileList = $configArray['Access']['RefererDomainWhileList'];
        $RefererDomainWhileList = empty($RefererDomainWhileList) ? array() : $RefererDomainWhileList;
        if(in_array($refererHost,$RefererDomainWhileList) === false){
           $this->showMsg("referer limit");
		   return;
        }

        $filterChain->execute();
    }
    
    public function showMsg($msg){
        $log = new TMLog();
        $log->ll("TMUrlRefererCheckFilter:".$msg);
        
        echo $msg;exit;
    }
    

}
