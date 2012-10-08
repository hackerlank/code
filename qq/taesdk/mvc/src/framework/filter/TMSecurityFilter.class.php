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
 * 流程链--安全控制类
 *
 * LIB库内部调用
 * 目前而言主要是指是否需要对网站进行访问ip控制
 * 如果开启了此控制，则只有在config/validated_ip.yml名单中的ip才有权限访问
 * 开关：config/filter.yml - TMSecurityFilter
 *
 * @package sdk.mvc.src.framework.filter
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMSecurityFilter.class.php 2009-4-16 by ianzhang
 * @version TMSecurityFilter.class.php 2010-1-21 by ryanfu
 */
class TMSecurityFilter extends TMFilter
{
    private $currTime = NULL;
    private $con = NULL;
    private $act = NULL;

    protected static $enableSecurity = true;

    public static function getEnableSecurity()
    {
        return self::$enableSecurity;
    }

    public static function setEnableSecurity($enableSecurity)
    {
        self::$enableSecurity = $enableSecurity;
    }

    /**
     * 执行安全逻辑
     * 目前而言指增加ip访问控制
     *
     * @param TMFilterChain $filterChain
     * @return void
     */
    public function execute($filterChain)
    {
        if(self::$enableSecurity){
            $ip = TMUtil::getClientIp();
            $Security_ary = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH.'config/filter/validated_ip.yml');

            if(!empty($Security_ary) && is_array($Security_ary))
            {
                foreach($Security_ary as $v)
                {
                    if(!empty($v['start']))
                    {
                        if(empty($this->currTime))
                        {
                            $this->currTime = time();
                        }
                        $startTime = strtotime($v['start']);
                        $endTime   = strtotime($v['end']);
                        if($this->currTime < $startTime || $this->currTime > $endTime)
                        {
                            continue;
                        }
                    }
                    if(!empty($v['con']))
                    {
                        if(empty($this->con))
                        {
                            $dispatcher     = TMDispatcher::getInstance();
                            $controllerName = $dispatcher->getController();
                            $actionName        = $dispatcher->getAction();
                            if(empty($controllerName)) $controllerName = 'default';
                            if(empty($actionName))       $actionName = 'default';
                            $this->con = $controllerName;
                            $this->act = $actionName;
                        }
                        if(empty($v['act']))
                        {
                            if($this->con != $v['con'])
                            {
                                continue;
                            }
                        }
                        else
                        {
                            if($this->act != $v['act'] || $this->con != $v['con'])
                            {
                                continue;
                            }
                        }
                    }
                    $array_ips = $v["ip"];
                    if(!empty($array_ips) && is_array($array_ips) && in_array($ip, $array_ips))
                    {
                        continue;
                    }
                    $response = TMDispatcher::getInstance()->getResponse();
                    $response->setContent("Your ip ($ip) is not allowed to access this page.");
                    return;
                }
            }
            else
            {
                $response = TMDispatcher::getInstance()->getResponse();
                $response->setContent("Your ip ($ip) is not allowed to access this page.");
                return;
            }
        }
        $filterChain->execute();
    }
}