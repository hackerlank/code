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
 * 白名单QQ登陆，自动化测试时使用
 *
 * 开关：config/filter.yml - TMWhileQQLoginFilter
 * 配置：config/filter/while.qq.login.yml
 * @package sdk.mvc.src.framework.filter
 * @author  gastonwu <gastonwu@tencent.com>
 */
class TMWhileQQLoginFilter extends TMFilter {

    /**
     * 白名单QQ登陆
     *
     * @param TMFilterChain $filterChain
     */
    public function execute($filterChain) {
        $isOnline =  isset($_ENV['SERVER_TYPE'] ) ? ($_ENV['SERVER_TYPE'] == "production") : true;
        if( $isOnline === true ){
            $filterChain->execute();
			return;
        }
        
        //如果cookie中有uin
        $uin = empty($_COOKIE['uin']) ? "" :  substr($_COOKIE['uin'],1);
        $uin = trim($uin);
        
        if(empty($uin)){
            $filterChain->execute();
			return;
        }
        //如果第一位为0，直接去掉
        $uin = $uin[0] === '0' ? substr($uin,1) : $uin;
        //并且uin存在白名单中，就将uin放_ENV['loginQQ']中
        $configArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/filter/while.qq.login.yml");
        if(in_array($uin,$configArray)){
            $_ENV['loginQQ'] = $uin;
        }

        $filterChain->execute();
    }
    

}
