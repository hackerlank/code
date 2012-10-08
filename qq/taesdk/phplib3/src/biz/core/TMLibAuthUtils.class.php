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
 * The Auth Utils
 *
 * @package lib.util
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-11
 * @version 2009-10-15 ianzhang
 */
class TMLibAuthUtils{
    /**
     * Whether qq is logined
     * @param  int $appid
     *
     * @return rboolean
     */
    public static function isLogin($appid, &$loginQQ, $pl2SessNameUin, $pl2SessNameKey, $pl2SessServer1Host, $pl2SessServer1Port, $pl2SessServer2Host, $pl2SessServer2Port)
    {
        $request = TMWebRequest::getInstance();
        $response = TMWebResponse::getInstance();

        if (isset($loginQQ))
        {
            if(empty($loginQQ))
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        $iRet = self::getUinFromCookie();
        TMDebugUtils::debugLog("Logined_fake_qq is :".$iRet);
        if (!preg_match("/^[1-9][0-9]{4,15}$/", $iRet))
        {
            $response->setCookie("uin", '', 0, "/", "qq.com");
            $response->setCookie("skey", '', 0, "/", "qq.com");
            $loginQQ = 0;
            return false;
        }
        else
        {
            $loginQQ = $iRet;
            return true;
        }
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
    public static function verifyVkey($vkey, $appid, $expire, $pl2VcNameSession, $pl2VkeyServer1Host, $pl2VkeyServer1Port, $pl2VkeyServer2Host, $pl2VkeyServer2Port)
    {
        if(strtolower($vkey) == 'abcd') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get uin from ptlogin2
     *
     * @param  int $appid           appid for logining
     * @param  boolean $verify      Whether to get user detail info
     *
     * @return  int       >=10000  QQ uin number.   <10000  Error Code
     * @throws TMNoLoginException
     */
    public static function getUin($appid, &$loginQQ, $verify, $pl2SessNameUin, $pl2SessNameKey, $pl2SessServer1Host, $pl2SessServer1Port, $pl2SessServer2Host, $pl2SessServer2Port)
    {
        $request = TMWebRequest::getInstance();
        $response = TMWebResponse::getInstance();

        if (isset($loginQQ))
        {
            if(empty($loginQQ))
            {
                $loginQQ = 0;
                throw new TMNoLoginException("请您先登录QQ号码");
            }
            else
            {
                return $loginQQ;
            }
        }
        $qq =  self::getUinFromCookie();
        TMDebugUtils::debugLog("Logined_fake_qq is :".$qq);
        if(!preg_match("/^[1-9][0-9]{4,9}$/", $qq))
        {
            $response->setCookie("uin", '', 0, "/", "qq.com");
            $response->setCookie("skey", '', 0, "/", "qq.com");
            $loginQQ = 0;
            throw new TMNoLoginException("请您先登录QQ号码");
        }
        else
        {
            $loginQQ = $qq;
            return $qq;
        }
    }

    /**
     * Get user info from ptlogin2
     *
     * @param int $appid appid for logining
     *
     * @return array
     */
    public static function getUserInfo($appid, $pl2SessNameUin, $pl2SessNameKey, $pl2SessServer1Host, $pl2SessServer1Port, $pl2SessServer2Host, $pl2SessServer2Port)
    {
        $loginQQ = null;
        $uin = self::isLogin($appid, $loginQQ, $pl2SessNameUin, $pl2SessNameKey, $pl2SessServer1Host, $pl2SessServer1Port, $pl2SessServer2Host, $pl2SessServer2Port)
            ? self::getUin($appid, $loginQQ, true, $pl2SessNameUin, $pl2SessNameKey, $pl2SessServer1Host, $pl2SessServer1Port, $pl2SessServer2Host, $pl2SessServer2Port)
            : 0;
        return empty($uin) ? array() : array(
            'nickname' => 'Test outsource',
            'nick' => 'Test outsource',
            'logintime' => time(),
            'accesstime' => time(),
            'face' => 54,
            'age' => 28,
            'gender' => 1,
            'password' => '',
            'mail' => ''
        );
    }


    /**
     * 通过公司的UDS服务来获取qq号码的年龄,性别,省份
     * 不需要登录态
     *
     * @param   string    $qq
     *
     * @return array    array that contains user info
     */
    public static function getUDSInfo($qq, $userinfoUdsUrl)
    {
        $pools = array(
            array('province' => '0',  'city' => '0'),    // （未填写）
            array('province' => '18', 'city' => '181'),  // 河南，郑州
            array('province' => '19', 'city' => '199'),  // 广州，东莞
            array('province' => '6',  'city' => '35'),   // 江苏，南京
        );
        $location = $pools[array_rand($pools)];
        return array_merge(array(
            'qq'       => $qq,
            'age'      => '28',
            'gender'   => "1",
        ),$location);
    }

    /**
     * 检查一个QQ号码是否是黄钻用户
     * 【FAKE专属】根据开发需要，可以自行调整返回结果。
     * 注意！！！在正式运营环境中，传入参数仅支持 $qq
     *
     * @param unsigned int $qq QQ号码
     * @param boolean $qzone  【FAKE专属】期望的QZone开通状态
     * @param boolean $yellow 【FAKE专属】期望的黄钻开通状态
     * @param boolean $city   【FAKE专属】期望的城市达人开通状态
     * @return boolean    array that contains user info
     */
    public static function checkYellow($qq, $qzone=0, $yellow=0, $city=0) {
        $qzone  = (int)(boolean)$qzone;
        $yellow = (int)(boolean)$yellow;
        $city   = (int)(boolean)$city;
        return json_encode(array('data'=>array('qzone'=>$qzone,'yellow'=>$yellow,'city'=>$city)));
    }

    /**
     * 获取QQShow头像地址  70*113 pix
     *
     * @param string $qq
     *
     * @return string qqshow 头像地址
     */
    public static function getQQShowAvatar($qq)
    {
        return 'http://qqshow-user.tencent.com/' . $qq . '/11/00/';
    }


    /**
     * 获取QQShow头像地址  140*226 pix
     *
     * @param string $qq
     *
     * @return string qqshow 头像地址
     */
    public function getBigQQShowAvatar($qq)
    {
        return 'http://qqshow-user.tencent.com/' . $qq . '/10/00/';
    }

    /**
     * 根据 APPID 取得cookie的key。
     * 意味着同一APPID下的外包开发项目，登陆态是统一的
     */
    private static function getUinFromCookie() {
        $regex = '/^\w{0,1}0*([1-9]\d{4,9})$/';
        $existsCookieUin = empty($_COOKIE['uin']) ? 0 : preg_match($regex,$_COOKIE['uin']);
        $uin = $existsCookieUin ? preg_replace($regex, "$1", $_COOKIE['uin']) : 0;
        
        return floor(floatval($uin))==$uin ? floor(floatval($uin)) : 0;
    }
}