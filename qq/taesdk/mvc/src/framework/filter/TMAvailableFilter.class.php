<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2009 BY  TENCENT  CORPORATION.  ALL RIGHTS
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
 * 上下线控制逻辑类
 *
 * LIB库内部调用
 * 可以控制每个controller、action的上下线时间
 * 开关：config/filter.yml - TMAvailableFilter
 * 具体配置：config/available.yml
 *
 * @package sdk.mvc.src.framework.filter
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMAvailableFilter.class.php 2009-4-16 by ianzhang
 */
class TMAvailableFilter extends TMFilter
{
    private $availableConfigName;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->loadConfiguration();
    }

    /**
     * 获取上下线配置
     */
    private function loadConfiguration()
    {
        $configDispatcher = new TMConfigDispatcher();
        require($configDispatcher->getConfigFile("available"));
        $this->availableConfigName = $availableConfigName;
    }

    /**
     * 执行上下线控制逻辑
     *
     * @param TMFilterChain $filterChain
     */
    public function execute($filterChain)
    {
        $apcMgr = new TMAPCMgr(0, $this->availableConfigName);
        $array = $apcMgr->get("availableList");

        if($array == false)
        {
            $array = TMYaml::load(ROOT_PATH."config/filter/available.yml");
            $availableList = $array["availableList"];
            if(!is_array($availableList))
            {
                $availableList = array();
            }
            $apcMgr->set("availableList",$availableList);
            $array = $apcMgr->get("availableList");
        }

        $dispatcher = TMDispatcher::getInstance();
        $controllerName = $dispatcher->getController();
        if(empty($controllerName))
        {
            $pageName = "default";
        }
        else
        {
            $pageName = $dispatcher->getController().$dispatcher->getAction();
        }

        $isNeeded = false;
        if (is_array($array))
        {
            foreach($array as $pageArray)
            {
                if($pageName == $pageArray["name"])
                {
                    $isNeeded = true;
                    $fromDate = $pageArray["fromdate"];
                    $toDate = $pageArray["todate"];
                    $isAjax = $pageArray["isAjax"];
                    $returnAddress = $pageArray["return"];
                    $frommessage = $pageArray["frommessage"];
                    $tomessage = $pageArray["tomessage"];
                    break;
                }
            }
        }

        if(!$isNeeded)
        {
            $filterChain->execute();
        }
        else
        {
            if(time() <= strtotime($fromDate))
            {
                if($isAjax == "ajax")
                {
                    $content = json_encode ( array ("code" => 100
                                , "message" => $frommessage));
                    echo  $content;
                }
                else
                {
                    $controllerName  = $dispatcher->getController()."Controller";
                    require_once ROOT_PATH.'controllers/'.$controllerName.".php";
                    $controller = new $controllerName(null,null,null);
                    $content = $controller->sendAlertBack($frommessage,$returnAddress);

                    echo  $content;
                }
            }
            else if(time()>=strtotime($toDate))
            {
                if($isAjax == "ajax")
                {
                    $content = json_encode ( array ("code" => 100
                                , "message" => $tomessage));
                    echo  $content;
                }
                else
                {
                    $controllerName  = $dispatcher->getController()."Controller";
                    require_once ROOT_PATH.'controllers/'.$controllerName.".php";
                    $controller = new $controllerName(null,null,null);
                    $content = $controller->sendAlertBack($tomessage,$returnAddress);

                    echo  $content;
                }
            }
            else
            {
                $filterChain->execute();
            }
        }
    }
}