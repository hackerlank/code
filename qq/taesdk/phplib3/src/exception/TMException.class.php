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
 * The general Exception class of Tecent AD Platform
 * @package sdk.lib3.src.exception
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-1
 */
class TMException extends Exception
{
    protected static $loggerInstances = array();

    protected static $needShowRealMsg = false;

    protected static $needAutoRedirect = false;

    public static function getNeedShowRealMsg()
    {
        return self::$needShowRealMsg;
    }

    public static function setNeedShowrealMsg($needShowRealMsg)
    {
        self::$needShowRealMsg = $needShowRealMsg;
    }

    public static function getNeedAutoRedirect()
    {
        return self::$needAutoRedirect;
    }

    public static function setNeedAutoRedirect($needAutoRedirect)
    {
        self::$needAutoRedirect = $needAutoRedirect;
    }

    /**
     * 增加一个监测日志类
     *
     * @param TMLogInterface $loggerInstance
     */
    public static function addLogger(TMLogInterface $loggerInstance)
    {
        self::$loggerInstances[] = $loggerInstance;
    }

    /**
     * 构造函数
     *
     * @param string $message
     * @param int $code
     */
    public function __construct($message='', $code=TMLibExceptionConstant::EXCEPTION_DEFAULT_CODE)
    {
        parent::__construct ($message, $code);
    }

    /**
     * 将异常记录到日志中
     *
     * @param string $message
     */
    protected function logException($message)
    {
        foreach(self::$loggerInstances as $loggerInstance)
        {
            $loggerInstance->la($message);
        }
    }

    /**
     * 异常处理，输出显示给用户
     *
     * @param string $tpl
     * @param string $message
     * @return string
     */
    public function handle($tpl='', $message='')
    {
        return $this->output($tpl, $message);
    }

    /**
     * 输出异常页面
     *
     * @param string $tpl 模板文件在templates中的相对路径
     * @param string $message 显示给用户的异常信息
     */
    public function output($tpl='', $message='')
    {
        if (empty($message))
        {
            $message = $this->getMessage();
        }

        if(class_exists("TMExceptionView"))
        {
            $view = new TMExceptionView();
            $data = array(
            "autoRidrect"   => self::$needAutoRedirect,
            "errorMsg"  => $message,
            "code" => $this->getCode()
            );
            return $view->renderException($data, $tpl);
        }else{
            return $message;
        }

    }
}