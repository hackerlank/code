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
 require_once FRAMEWORK_PATH.'../config/ptlogin.config.php';

/**
 * TMAuthUtils
 * 用户权限相关操作类
 *
 * @package sdk.mvc.src.framework.util
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMAuthUtils.class.php 2010-12-27 by ianzhang    
 */
class TMAuthUtils extends TMLibAuthUtils{
    
    protected static $isLLogin = false;
    
    public static function setIsLLogin($isLLogin)
    {
        self::$isLLogin = $isLLogin;    
    }
    
    public static function getIsLLogin()
    {
        return self::$isLLogin;
    }
    
    /**
     * 判断是否登录
     * @param int $appid
     * @return boolean
     */
    public static function isLogin($appid = null) {
        if(self::$isLLogin){
            return self::isLLogin($appid);
        }
        if(empty($appid))
        {
            $appid = TMConfig::get("appid");
        }
        $_ENV["loginQQ"] = isset($_ENV["loginQQ"]) ? $_ENV["loginQQ"] : null;
        return parent::isLogin(
            $appid, $_ENV["loginQQ"],
            PL2_SESS_NAME_UIN,     PL2_SESS_NAME_KEY,
            PL2_SESS_SERVER1_HOST, PL2_SESS_SERVER1_PORT,
            PL2_SESS_SERVER2_HOST, PL2_SESS_SERVER2_PORT
        );
    }
    
    /**
     * 判断是否弱登录
     * @param int $appid
     * @return boolean
     */
    public static function isLLogin($appid = null) {
        if(empty($appid))
        {
            $appid = TMConfig::get("appid");
        }
        $_ENV["loginQQ"] = isset($_ENV["loginQQ"]) ? $_ENV["loginQQ"] : null;
        return parent::isLogin(
            $appid, $_ENV["loginQQ"],
            PL2_SESS_NAME_UIN,     PL2_SESS_NAME_KEY,
            PL2_SESS_SERVER1_HOST, PL2_SESS_SERVER1_PORT,
            PL2_SESS_SERVER2_HOST, PL2_SESS_SERVER2_PORT,
            PL2_LSESS_NAME_UIN, PL2_LSESS_NAME_KEY
        );
    }

    /**
     * Verify the picture code
     *
     * @param   string  $vkey     vkey code input by user
     * @param   int[optional=0]  $appid      Application ID.
     * @param   int[optional]    $expire     expire time.
     *
     * @return  result           the verify result
     */
    public static function verifyVkey($vkey, $appid=null, $expire=null) {
        $appid  = isset($appid)  ? $appid  : TMConfig::get("appid");
        $expire = isset($expire) ? $expire : PL2_VKEY_VALIDTIME;
        return parent::verifyVkey(
            $vkey, $appid, $expire,
            PL2_VC_NAME_SESSION,
            PL2_VKEY_SERVER1_HOST, PL2_VKEY_SERVER1_PORT,
            PL2_VKEY_SERVER2_HOST, PL2_VKEY_SERVER2_PORT
        );
    }

    /**
     * 获得当前登录QQ号码
     * @param int $appid
     * @param boolean $verify
     * @return float
     */
    public static function getUin($appid = null, $verify = true) {
        if(self::$isLLogin){
            return self::getLUin($appid, $verify);
        }
        if(empty($appid))
        {
            $appid = TMConfig::get("appid");
        }
        // the second param, $_ENV['loginQQ'] is a reference
        $_ENV["loginQQ"] = isset($_ENV["loginQQ"]) ? $_ENV["loginQQ"] : null;
        return parent::getUin(
            $appid, $_ENV['loginQQ'], $verify,
            PL2_SESS_NAME_UIN,     PL2_SESS_NAME_KEY,
            PL2_SESS_SERVER1_HOST, PL2_SESS_SERVER1_PORT,
            PL2_SESS_SERVER2_HOST, PL2_SESS_SERVER2_PORT
        );
    }
    
    /**
     * 获得当前弱登录QQ号码
     * @param int $appid
     * @param boolean $verify
     * @return float
     */
    public static function getLUin($appid = null, $verify = true) {
        if(empty($appid))
        {
            $appid = TMConfig::get("appid");
        }
        // the second param, $_ENV['loginQQ'] is a reference
        $_ENV["loginQQ"] = isset($_ENV["loginQQ"]) ? $_ENV["loginQQ"] : null;
        return parent::getUin(
            $appid, $_ENV['loginQQ'], $verify,
            PL2_SESS_NAME_UIN,     PL2_SESS_NAME_KEY,
            PL2_SESS_SERVER1_HOST, PL2_SESS_SERVER1_PORT,
            PL2_SESS_SERVER2_HOST, PL2_SESS_SERVER2_PORT,
            PL2_LSESS_NAME_UIN, PL2_LSESS_NAME_KEY
        );
    }

    /**
     * 获得用户信息
     * @param int $appid
     * @return array 
     */
    public static function getUserInfo($appid = null) {
        if(self::$isLLogin){
            return self::getLUserInfo($appid);
        }
        if(empty($appid))
        {
            $appid = TMConfig::get("appid");
        }
        return parent::getUserInfo(
            $appid,
            PL2_SESS_NAME_UIN,     PL2_SESS_NAME_KEY,
            PL2_SESS_SERVER1_HOST, PL2_SESS_SERVER1_PORT,
            PL2_SESS_SERVER2_HOST, PL2_SESS_SERVER2_PORT
        );
    }
    
    /**
     * 获得弱登录用户信息
     * @param int $appid
     * @return array 
     */
    public static function getLUserInfo($appid = null) {
        if(empty($appid))
        {
            $appid = TMConfig::get("appid");
        }
        return parent::getUserInfo(
            $appid,
            PL2_SESS_NAME_UIN,     PL2_SESS_NAME_KEY,
            PL2_SESS_SERVER1_HOST, PL2_SESS_SERVER1_PORT,
            PL2_SESS_SERVER2_HOST, PL2_SESS_SERVER2_PORT,
            PL2_LSESS_NAME_UIN, PL2_LSESS_NAME_KEY
        );
    }

    /**
     * 获得QQ的UDS信息
     * @param string $qq
     * @return array
     */
    public static function getUDSInfo($qq) {
        $userinfoUdsUrl = TMConfig::get("udsinfo","path");
        return parent::getUDSInfo($qq, $userinfoUdsUrl);
    }

}
?>