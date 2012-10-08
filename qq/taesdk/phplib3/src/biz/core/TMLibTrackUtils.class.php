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
 * The util class for tracking
 *
 * @package lib.util
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMTrackUtils.class.php 2009-9-27 by ianzhang
 */
class TMLibTrackUtils
{
    private static $socket;
    
    /**
     * 发送Qzone日志
     *
     */
    const SEND_BLOG = 6000113;
    /** 
     * @todo fill more action ids, such as REGISTER_SUCESSFUL and so on 
     */
    
    // CURL要使用到的句柄
    private static $_channel = null;
    
    // CURL的url地址
    private static $_url = null;
    
    // CURL的指定主机IP(非必要)
    private static $_hostIP = null;
    
    // CURL需要用的参数
    private static $_options = array();
    
    // CURL需要用的参数
    private static $_timeOut = 1;
    
    /**
     * 初始化Track要使用到的CURL功能
     *
     */
    private static function _init($url, $hostIp) {
        self::$_channel = curl_init();
        self::$_url = $url;
        self::$_hostIP = $hostIp;
        
        self::_setOption(CURLOPT_HEADER, 0);
        self::_setOption(CURLOPT_COOKIE, TMUtil::handleParameter($_COOKIE, "; "));
        self::_setOption(CURLOPT_RETURNTRANSFER, 1);
        if (self::$_hostIP) {
            $urlInfo = parse_url(self::$_url);
            self::_setOption(CURLOPT_HTTPHEADER, array('Host: ' . $urlInfo['host']));
            self::$_url = 'http://' . self::$_hostIP;
        }
    }
    
    /**
     * 设置CRUL参数
     *
     * @param string $key CURL参数的键名
     * @param mix $val CURL参数的值
     */
    private static function _setOption($key, $val)
    {
        self::$_options[$key] = $val;
    }
    
    /**
     * 得到CURL需要的参数
     *
     * @return array $options CURL需要的参数
     */
    private static function _getOptions()
    {
        return self::$_options;
    }
      
    /**
     * 设置Curl的超时时间（秒为单位）
     *
     * @param unknown_type $seconds 超时秒数
     */
    private static function setTimeout($seconds)
    {
        if (is_numeric($seconds) && $seconds > 0) {
            self::$_timeOut = $seconds;
        }
    }
    
    /**
     *
     * @param  string $qq           QQ号码
     * @param  int $actionId        行为ID
     * @param  string $campaignId   活动号（选填）
	 * @param  TMLogInterface $log  Logger的实例化对象
     * @return boolean $result       true: 成功, false:失败
     */
    public static function trackAction($qq, $actionId, $campaignId)
    {
		// 记录track信息到日志文件
		// added by simonkuang, on 2011/3/31
		$logfile = 'track.log';
		$fp = @fopen ( $logfile, 'w+b' );
		if ( empty($fp) )
		{
			return '';
		}
		$line = '[TRACK] '.str_pad ( $qq, 10, ' ', STR_PAD_LEFT ).' '.$actionId.' '.$campaignId."\n";
		@fwrite ( $fp, $line );
		@fclose ( $fp );
        return '';
    }

}


