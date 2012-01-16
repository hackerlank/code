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
 * Advertisement Platform LOG
 *
 * @package sdk.lib3.src.base.core
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-8
 */
class TMLibLog implements TMLogInterface
{
    protected static $logRecords = array();
    protected $file;

    protected $path;

    const statusHigh   = "H";
    const statusMiddle = "M";
    const statusLow    = "L";
    const statusAlert  = "A";

	protected static $colorFront   = array(
		'black'  => 30,
		'red'    => 31,
		'green'  => 32,
		'yellow' => 33,
		'blue'   => 34,
		'purple' => 35,
		'darkgreen' => 36,
		'white'  => 37,
	);

	protected static $colorBg   = array(
		'black'   => 40,
		'red'     => 41,
		'green'   => 42,
		'yellow'  => 43,
		'blue'    => 44,
		'purple'  => 45,
		'darkgreen' => 46,
		'white'   => 47,
	);

	protected static $needThrowException = false;

    /**
     * 构造函数
     *
     * @param string  $path    日志文件的路径（完整路径）
     * @param boolean $mutiple 支持分文件存储
     * @param int     $logSize 如果支持分文件，该值为分文件的临界值
     */
    public function __construct($path, $multiple = true, $logSize = 134217728)
    {
        $this->path = $path;
        if(is_file($path) && $multiple === true){
            $filesize = @filesize($path);
            if (!empty($filesize) && $filesize > $logSize)
            {
                $lockServ = new LockService();
                if ($lockServ->lock($path.'_log_file_path', 10))
                {
                    @unlink($path."_php_4");
                    if(@rename($path."_php_3", $path."_php_4") === TRUE)
                    {
                        @chmod($path."_php_4", 0777);
                    }
                    if(@rename($path."_php_2", $path."_php_3") === TRUE)
                    {
                        @chmod($path."_php_3", 0777);
                    }
                    if(@rename($path, $path."_php_2") === TRUE)
                    {
                        @chmod($path."_php_2", 0777);
                    }
                    @unlink($path);
                    $lockServ->unlock($path.'_log_file_path');
                }
            }

            if(is_file($path."_2")){
                $filesize = @filesize($path."_2");
                if (!empty($filesize) && $filesize > $logSize)
                {
                    $lockServ = new LockService();
                    if ($lockServ->lock($path.'_2_log_file_path', 10))
                    {
                        @unlink($path."_2_php_4");
                        if(@rename($path."_2_php_3", $path."_2_php_4") === TRUE)
                        {
                            @chmod($path."_2_php_4", 0777);
                        }
                        if(@rename($path."_2_php_2", $path."_2_php_3") === TRUE)
                        {
                            @chmod($path."_2_php_3", 0777);
                        }
                        if(@rename($path."_2", $path."_2_php_2") === TRUE)
                        {
                            @chmod($path."_2_php_2", 0777);
                        }
                        @unlink($path."_2");
                        $lockServ->unlock($path.'_2_log_file_path');
                    }
                }
            }

            if(is_file($path."_2_3")){
                $filesize = @filesize($path."_2_3");
                if (!empty($filesize) && $filesize > $logSize)
                {
                    $lockServ = new LockService();
                    if ($lockServ->lock($path.'_2_3_log_file_path', 10))
                    {
                        @unlink($path."_2_3_php_4");
                        if(@rename($path."_2_3_php_3", $path."_2_3_php_4") === TRUE)
                        {
                            @chmod($path."_2_3_php_4", 0777);
                        }
                        if(@rename($path."_2_3_php_2", $path."_2_3_php_3") === TRUE)
                        {
                            @chmod($path."_2_3_php_3", 0777);
                        }
                        if(@rename($path."_2_3", $path."_2_3_php_2") === TRUE)
                        {
                            @chmod($path."_2_3_php_2", 0777);
                        }
                        @unlink($path."_2_3");
                        $lockServ->unlock($path.'_2_3_log_file_path');
                    }
                }
            }
        }
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {

    }

    /**
     * Log High priority （高优先级日志）
	 *
     * @param string $msg     the log message
     */
    public function lh($msg)
    {
        $this->formatWrite($msg,self::statusHigh);
    }

    /**
     * Log Middle priority （中优先级日志）
	 *
     * @param string $msg     the log message
     */
    public function lm($msg)
    {
        $this->formatWrite($msg,self::statusMiddle);
    }

    /**
     * Log Low priority （低优先级日志）
     *
     * @param string $msg     the log message
     */
    public function ll($msg)
    {
        $this->formatWrite($msg,self::statusLow);
    }

    /**
     * 记录Alert级别的日志
     * @param string $msg
     */
    public function la($msg)
    {
        $this->formatWrite($msg,self::statusAlert);
    }

    /**
     * 记录无关日志
     * @param string $msg
     */
    public function lo($msg)
    {
        $this->formatWrite($msg,'',self::$colorFront['darkgreen']);
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
		// 设置控制字符
		$cntlChar = chr(033);

		// 前景色：$colorFront
		// 背景色：$colorBg

		// 校验前景色。如果没有设置正确的前景色，默认不高亮
		$colorFront = (int)$colorFront;
		$colorFront = in_array($colorFront,self::$colorFront) ? $colorFront : 0;
		// 校验背景色。如果没有设置正确的背景色，默认不高亮
		$colorBg = (int)$colorBg;
		$colorBg = in_array($colorBg,self::$colorBg) ? $colorBg : 0;

		// 有级别的日志记录，根据级别标志位来决定颜色。
		$status = strtoupper($status);
		switch($status) {
			case self::statusMiddle:
				$colorFront = self::$colorFront['yellow'];
				break;
			case self::statusLow:
				$colorFront = self::$colorFront['green'];
				break;
		    case self::statusAlert:
		    case self::statusHigh:
                $colorFront = self::$colorFront['red'];
                break;
		}

		// 写入日志
        try
        {
            if(!isset($this->file)){
                $this->file = new TMFile($this->path, "ab+");
            }

            $date = date("H:i:s Ymd");
            if (!empty($status))
            {
                $val = $cntlChar."[{$colorBg};{$colorFront};1m[".$date."] <".$status.">{$cntlChar}[0;0;0m ".$msg.".\n";
            }
            else
            {
                $val = $cntlChar."[{$colorBg};{$colorFront};1m[".$date."]{$cntlChar}[0;0;0m ".$msg.".\n";
            }
            if(empty($this->file)){
                throw new TMFileException ( "Failed to open the log file! Can't open the file");
            }
            $this->file->insert($val);
        }
        catch(TMException $te)
        {
            if(self::$needThrowException)
            {
                throw $te;
            }
        }
    }

    public static function getNeedThrowException()
    {
        return self::$needThrowException;
    }

    public static function setNeedThrowException($needThrowException)
    {
        self::$needThrowException = $needThrowException;
    }
}
