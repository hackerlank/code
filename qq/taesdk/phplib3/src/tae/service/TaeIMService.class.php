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
 * TAE IM接口服务
 *
 * @package sdk.lib3.src.tae.service
 * @author  happysonxu<happysonxu@tencent.com>
 * @version TaeIMService.class.php 2011-6-16 by happysonxu
 */
class TaeIMService {

    protected static $isTestENV = false;

    public static function getIsTestENV()
    {
        return self::$isTestENV;
    }

    public static function setIsTestENV($isTestENV)
    {
        self::$isTestENV = $isTestENV;
    }

	/**
	 * 获取用户好友列表。只能获取当前请求QQ号码的好友关系链。
	 * 默认从cookie中读取skey。也可传入参数指定skey。
	 *
	 * @param string $skey
	 * @return array
	 */
	public static function getFriendList($skey = null)
	{
	    $isTestENV = self::$isTestENV;
	    if($isTestENV){
	        $tmpUin = TaeCore::getHeader(TaeConstants::UIN);
	        TaeCore::taeInit(TaeConstants::UIN,'1002000273');
	    }
		if(empty($skey))
		{
			$skey = $_COOKIE['skey'];
		}
		$para = array("skey"=> $skey);

		if($isTestENV){
    		try{
                $result = TaeCore::taeCall(TaeConstants::CMD_GET_FRIEND_LIST,$para);
                TaeCore::taeInit(TaeConstants::UIN,$tmpUin);
    		}catch(TMException $te)
    		{
    		    TaeCore::taeInit(TaeConstants::UIN,$tmpUin);
    		    throw $te;
    		}
		}else{
		    $result = TaeCore::taeCall(TaeConstants::CMD_GET_FRIEND_LIST,$para);
		}

		return $result;
	}

	/**
	 * 批量取QQ昵称
	 * @param mixed $qq
	 * @return array
	 */
	public static function getNick($qq)
	{
	    $isTestENV = self::$isTestENV;
        if($isTestENV){
            $tmpUin = TaeCore::getHeader(TaeConstants::UIN);
            TaeCore::taeInit(TaeConstants::UIN,'1002000273');
        }
		if(is_array($qq))
		{
			$list = '';
			foreach ($qq as $one)
			{
				$list.=$one.";";
			}
			$qq = substr($list,0,strlen($list)-1);
		}
		$para = array("uinlist"=>$qq,"skey"=>"abc");

		if($isTestENV){
    		try{
                $result = TaeCore::taeCall(TaeConstants::CMD_GET_NICK,$para);
                TaeCore::taeInit(TaeConstants::UIN,$tmpUin);
            }catch(TMException $te)
            {
                TaeCore::taeInit(TaeConstants::UIN,$tmpUin);
                throw $te;
            }
		}else{
		    $result = TaeCore::taeCall(TaeConstants::CMD_GET_NICK,$para);
		}

        return $result;
	}

	/**
	 * 获得好友分组
	 * @param $skey
	 * @return array
	 */
	public static function getFriendGroup($skey = null)
	{
        $isTestENV = self::$isTestENV;
        if($isTestENV){
            $tmpUin = TaeCore::getHeader(TaeConstants::UIN);
            TaeCore::taeInit(TaeConstants::UIN,'1002000273');
        }
		if(empty($skey))
		{
			$skey = $_COOKIE['skey'];
		}
		$para = array("skey"=> $skey);

		if($isTestENV){
    		try{
                $result = TaeCore::taeCall(TaeConstants::CMD_GET_FRIEND_GROUP,$para);
                TaeCore::taeInit(TaeConstants::UIN,$tmpUin);
            }catch(TMException $te)
            {
                TaeCore::taeInit(TaeConstants::UIN,$tmpUin);
                throw $te;
            }
		}else{
		    $result = TaeCore::taeCall(TaeConstants::CMD_GET_FRIEND_GROUP,$para);
		}

        return $result;
	}
}