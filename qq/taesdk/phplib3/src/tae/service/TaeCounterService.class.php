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
 * TAE 计数服务
 *
 * @package sdk.lib3.src.tae.service
 * @author  happysonxu<happysonxu@tencent.com>
 * @version TaeCounterService.class.php 2011-6-16 by happysonxu
 */
class TaeCounterService{

	const STRICT_NONE = 0;
	const STRICT_MAX = 1;
	const STRICT_LAST = 2;
	const ERR_LIMIT = -3050;
	/**
	 * 查询计数,按天计数
	 *
	 * @param int $counter_id
	 * @param int $key
	 * @param int $extra_key
	 * @return array
	 */	
	public static function dayCounterQuery($counter_id, $key, $extra_key = 0)
	{
		if($extra_key==0){
			$extra_key = (int) date("Ymd");
		}
		$para = array("counter_id"=>$counter_id,"key"=>$key,"extra_key"=>$extra_key);
		$result = TaeCore::taeCall(TaeConstants::CMD_COUNTER_QUERY_DAY,$para);
		return self::processResult($result);
	}
	
	/**
	 * 查询计数，总量计数
	 *
	 * @param int $counter_id
	 * @param int $key
	 * @param int $extra_key
	 * @return array
	 */
	public static function counterQuery($counter_id, $key, $extra_key = 0)
	{
		$para = array("counter_id"=>$counter_id,"key"=>$key,"extra_key"=>$extra_key);
		$result = TaeCore::taeCall(TaeConstants::CMD_COUNTER_QUERY_ALL,$para);
		return self::processResult($result);
	}
	/**
	 * 增加计数，按天计数
	 *
	 * @param int $counter_id
	 * @param int $key
	 * @param int $add_val
	 * @param int $strict_type
	 * @param int $strict_val
	 * @return array
	 */
	public static function dayCounterAdd($counter_id, $key, $add_val,  $strict_type = 0, $strict_val = 0)
	{
		$extra_key = (int)date("Ymd");
		$para = array("counter_id"=>$counter_id,"key"=>$key,"add_val"=>$add_val,"extra_key"=>$extra_key,"strict_type"=>$strict_type,"strict_val"=>$strict_val);
		$result = TaeCore::taeCall(TaeConstants::CMD_COUNTER_ADD_DAY,$para);
		return self::processResult($result);
	}
/**
	 * 增加计数,总数计数
	 *
	 * @param int $counter_id
	 * @param int $key
	 * @param int $add_val
	 * @param int $extra_key
	 * @param int $strict_type
	 * @param int $strict_val
	 * @return array
	 */
	public static function counterAdd($counter_id, $key, $add_val, $extra_key = 0, $strict_type = 0, $strict_val = 0)
	{
		$para = array("counter_id"=>$counter_id,"key"=>$key,"add_val"=>$add_val,"extra_key"=>$extra_key,"strict_type"=>$strict_type,"strict_val"=>$strict_val);
		$result = TaeCore::taeCall(TaeConstants::CMD_COUNTER_ADD_ALL,$para);
		return self::processResult($result);
	}
	/**
	 * 处理返回的数组，去掉curr_value_returned字段
	 *
	 * @param array &$result
	 * @return array
	 */
	private static function processResult(&$result){
		unset($result['cur_value_returned']);
		return $result;
	}
	
	/**
	 * 查询计数,按天计数，扩展了字符串key字段,最长为16字节
	 *
	 * @param int $counter_id
	 * @param int $key
	 * @param int $extra_key
	 * @param string $extra_strkey
	 * @return array
	 */	
	public static function dayCounterQueryExt($counter_id, $key, $extra_key = 0,$extra_strkey='')
	{
		if($extra_key==0){
			$extra_key = (int) date("Ymd");
		}
		$para = array("counter_id"=>$counter_id,"key"=>$key,"extra_key"=>$extra_key,'extra_strkey'=>self::str2hex($extra_strkey));
		$result = TaeCore::taeCall(TaeConstants::CMD_COUNTER_QUERY_DAY_EXT,$para);
		return self::processResult($result);
	}
	
	/**
	 * 查询计数，总量计数，扩展了字符串key字段,最长为16字节
	 *
	 * @param int $counter_id
	 * @param int $key
	 * @param int $extra_key
	 * @param string $extra_strkey
	 * @return array
	 */
	public static function counterQueryExt($counter_id, $key, $extra_key = 0,$extra_strkey='')
	{
		$para = array("counter_id"=>$counter_id,"key"=>$key,"extra_key"=>$extra_key,'extra_strkey'=>self::str2hex($extra_strkey));
		$result = TaeCore::taeCall(TaeConstants::CMD_COUNTER_QUERY_ALL_EXT,$para);
		return self::processResult($result);
	}
	
	/**
	 * 增加计数，按天计数，扩展了字符串key字段,最长为16字节
	 *
	 * @param int $counter_id
	 * @param int $key
	 * @param int $add_val
	 * @param int $strict_type
	 * @param int $strict_val
	 * @param string $extra_strkey
	 * @return array
	 */
	public static function dayCounterAddExt($counter_id, $key, $add_val,$extra_strkey='', $strict_type = 0, $strict_val = 0 )
	{
		$extra_key = (int)date("Ymd");
		$para = array("counter_id"=>$counter_id,"key"=>$key,"add_val"=>$add_val,"extra_key"=>$extra_key,"strict_type"=>$strict_type,"strict_val"=>$strict_val,'extra_strkey'=>self::str2hex($extra_strkey));
		$result = TaeCore::taeCall(TaeConstants::CMD_COUNTER_ADD_DAY_EXT,$para);
		return self::processResult($result);
	}
	
	/**
	 * 增加计数,总数计数，扩展了字符串key字段,最长为16字节
	 *
	 * @param int $counter_id
	 * @param int $key
	 * @param int $add_val
	 * @param int $extra_key
	 * @param int $strict_type
	 * @param int $strict_val
	 * @param string $extra_strkey
	 * @return array
	 */
	public static function counterAddExt($counter_id, $key, $add_val, $extra_key = 0,$extra_strkey='', $strict_type = 0, $strict_val = 0)
	{
		$para = array("counter_id"=>$counter_id,"key"=>$key,"add_val"=>$add_val,"extra_key"=>$extra_key,"strict_type"=>$strict_type,"strict_val"=>$strict_val,'extra_strkey'=>self::str2hex($extra_strkey));
		$result = TaeCore::taeCall(TaeConstants::CMD_COUNTER_ADD_ALL_EXT,$para);
		return self::processResult($result);
	}
	/**
	 * 普通字符串转16进制字符串
	 * @param str $str
	 */
	private static function str2hex($str)
	{
		$len = strlen($str);
		$result = '';
		for($i=0;$i<$len;$i++)
		{
			$result.=dechex(ord($str[$i]));
		}
		return $result;
	}
}

