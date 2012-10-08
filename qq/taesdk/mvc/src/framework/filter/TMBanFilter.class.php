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
 * 禁止某些QQ号码进行活动参与
 *
 * @package sdk.mvc.src.framework.filter
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMBanFilter.class.php 2009-12-21 by ianzhang
 */
class TMBanFilter {
    private $configArray = array();

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->loadConfiguration();
    }

    /**
     * 获取配置
     */
    private function loadConfiguration()
    {
        $this->configArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/filter/ban.yml");
    }

    /**
     * 执行ban主逻辑
     *
     * @param TMFilterChain $filterChain
     */
    public function execute($filterChain) {
        $configArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/ban.yml");

        $dispatcher = TMDispatcher::getInstance();
        $response = $dispatcher->getResponse();
        $controllerName = $dispatcher->getController();
        $actionName = $dispatcher->getAction();

        $banList = $configArray["banList"];

        if(TMAuthUtils::isLogin())
        {
            $qq = TMAuthUtils::getUin();
            if(array_key_exists($qq, $banList))
            {
                if($banList[$qq]["type"] == "project")
                {
                    $response->setCookie("uin", '', 0, "/", "qq.com");
                    $response->setCookie("skey", '', 0, "/", "qq.com");
                    $response->setContent($response->getAlertBackString($configArray["message"]["project"],"/"));
                }
                else if($banList[$qq]["type"] == "controller" && in_array($controllerName,$banList[$qq]["controllers"]))
                {
                    $response->setCookie("uin", '', 0, "/", "qq.com");
                    $response->setCookie("skey", '', 0, "/", "qq.com");
                    $response->setContent($response->getAlertBackString($configArray["message"]["controller"],"/"));
                }
                else if($banList[$qq]["type"] == "action"
                  && array_key_exists($controllerName,$banList[$qq]["actions"])
                  && in_array($actionName, $banList[$qq]["actions"][$controllerName]))
                {
                    $response->setCookie("uin", '', 0, "/", "qq.com");
                    $response->setCookie("skey", '', 0, "/", "qq.com");
                    $response->setContent($response->getAlertBackString($configArray["message"]["action"],"/"));
                }
                else{
                    $filterChain->execute();
                }
            }
            else{
                $filterChain->execute();
            }
        }
        else{
            $filterChain->execute();
        }
    }
}

?>