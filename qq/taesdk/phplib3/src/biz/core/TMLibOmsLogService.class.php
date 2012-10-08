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

//simple code
//$o = OmsLogService::getInstance();
//echo $o->warn("haha\t".date("Ymd_Hi"));
//echo OmsLogService::getInstance()->warn("haha\t".date("Ymd_Hi"));
//echo "\n";

/**
 * OMS报警Log类
 *
 * @package sdk.lib3.src.biz.core
 * @author  gastonwu <gastonwu@tencent.com>
 */
class TMLibOmsLogService{
    protected static $instance = null;
    protected $logBaseDir = "";
    protected $siteLogDir = "";
    protected $tamsId;
    protected $maxFileSize = 0;

    /**
     * 构造函数
     * @param int $tamsId
     * @param string $logBaseDir
     * @return void
     */
    protected function __construct($tamsId,$logBaseDir=""){
        $this->tamsId = $tamsId;
        $siteLogDir = $logBaseDir . "/" . $this->tamsId."/";
        if(is_dir($siteLogDir) === false){
            $oldumask = umask(0);
            @mkdir($siteLogDir, 0777, true);
            umask($oldumask);
        }
        if(is_writable($siteLogDir) === false){

        }
        $this->siteLogDir = $siteLogDir;
    }

    /**
     * 记录错误信息
     * @param string $msg
     * @return boolean
     */
    public function error($msg){
        return $this->write('ERROR',$msg);
    }

    /**
     * 记录警告信息
     * @param string $msg
     * @return boolean
     */
    public function warn($msg){
        return $this->write('WARN',$msg);

    }

    /**
     * 记录info
     * @param string $msg
     * @return boolean
     */
    public function info($msg){
        return $this->write('INFO',$msg);
    }

    /**
     * 写日志
     * @param string $level
     * @param string $msg
     * @return boolean
     */
    protected function write($level,$msg){
        $msg = mb_convert_encoding($msg, "gbk", "utf-8");
        $logfile = $this->siteLogDir."/".date("Ymd").".log";
        $time = date("Y-m-d H:i:s");
        $line = "[$time][$level] $msg\n";
        $tag = @error_log($line,3,$logfile);
        return $tag;
    }
}