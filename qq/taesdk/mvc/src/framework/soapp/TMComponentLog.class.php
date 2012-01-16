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
 * TMComponentLog
 * 用于显示组件服务信息的日志类
 *
 * @package sdk.mvc.src.framework.soapp
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version class_name.class.php 2010-8-30 by ianzhang    
 */
class TMComponentLog{
    /**
     * @var TMComponentLog
     */
    private static $instance;
    /**
     * @var string
     */
    private $logFile;
    /**
     * @var boolean
     */
    private $debugMode;
    /**
     * @var boolean
     */
    private $traceMode;
    
    /**
     * 返回TMComponentLog
     * @static
     * @return TMComponentLog
     */
    public static function getInstance(){
        $obj = self::$instance;
        if($obj == null){
            self::$instance = new TMComponentLog();
            self::$instance->setLogFile(TMConfig::get("error_log", "path"));
            self::$instance->setDebugMode(TMConfig::get("debug_mode"));
            self::$instance->setTraceMode(TMConfig::get("debug_mode"));
        }
        return self::$instance;
    }
    
    /**
     * 设置日志文件路径
     */
    public function setLogFile($logFile){
        $this->logFile = $logFile;
    }
    
    /**
     * 设置是否开启了debug模式
     * @param boolean $debugMode
     */
    public function setDebugMode($debugMode){
        $this->debugMode = $debugMode;
    }

    /**
     * 设置是否开启了trace模式
     * @param boolean $traceMode
     */
    public function setTraceMode($traceMode){
        $this->traceMode = $traceMode;
    }
    
    /**
     * 打印debug信息
     * @param string $msg
     */
    public function debug($msg){
        if($this->debugMode !== true)
        {
            return;
        }        

        $line = "[component debug]\t".print_r($msg,true)."\n";
        if(empty($this->logFile)){
        	$log = new TMLog();
        }else{
            $log = new TMLog($this->logFile);
        }
        $log->lo($line);
    }
    
    /**
     * 打印trace信息
     * @param string $msg
     */
    public function trace($msg){
        if($this->traceMode !== true){
        	return;
        }

        $line = "[component trace]\t".print_r($msg,true)."\n";
        if(empty($this->logFile)){
            $log = new TMLog();
        }else{
            $log = new TMLog($this->logFile);
        }
        $log->lo($line);
    }
}