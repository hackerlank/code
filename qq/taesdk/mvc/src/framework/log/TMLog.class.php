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
 * TMLog
 * 日志程序
 *
 * @package sdk.mvc.src.framework.log
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMLog.class.php 2010-12-27 by ianzhang
 */
class TMLog extends TMLibLog{
    /**
     * 构造函数
     * @param string $path   日志地址
     * @param boolean $multiple
     * @return void
     */
    public function __construct($path = null, $multiple = true) {
        if(empty($path))
        {
            $path = TMConfig::get("error_log", "path");
        }
        if($path[0] != "/")
        {
            $path = ROOT_PATH.$path;
        }

        $splitRequest = TMConfig::get("error_log", "split_request");
        if(!(isset($splitRequest) && $splitRequest == false))
        {
            $dispatcher = TMDispatcher::getInstance();
            $componentName = $dispatcher->getComponent();
            $controllerName = $dispatcher->getController();
            $actionName = $dispatcher->getAction();

            if(!empty($componentName)){
                $path = $path."_{$componentName}";
            }

            if(!empty($controllerName)){
                $path = $path."_{$controllerName}";
            }

            if(!empty($actionName))
            {
                $path = $path."_{$actionName}";
            }
        }
        $logSize = 134217728; //128M
        $logSize = (TMConfig::get("error_log", "size") == null) ? $logSize : TMConfig::get("error_log", "size");

        parent::__construct($path, $multiple, $logSize);
    }

    /**
     * formatWrite
     * 格式化输出
     * echo chr(033).'[31;47;1mThis is a very important infomation.'.chr(033).'[0;0;0m'.chr(0x0a);
     *
     * @param string $msg      输出的log信息内容
     * @param string $status   输出标志位信息
     */
    protected function formatWrite($msg, $status="", $colorFront=null, $colorBg=null)
    {
        TMDebuggerManager::add("log", array($msg, $status));
        parent::formatWrite($msg, $status, $colorFront, $colorBg);
    }
}
?>
