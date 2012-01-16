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
 * Used to load all the class automatically
 *
 * @package sdk.lib3.src.biz.core
 * @author  Niko Niu <nikoniu@tencent.com> & Simon Kuang <simonkuang@tencent.com>
 * @since   2011/06/24
 */

/**
 * Methods:
 *
 *   1.  (array)   getRegion                 - 根据Code和地区类型（enum("COUNTRY","PROVINCE","CITY")）查找地区信息
 *   2.  (array)   getCountryByCode          - 根据国家编码取国家信息
 *   3.  (array)   getProvinceByCode         - 根据省份编码取省份信息
 *   4.  (array)   getCityByCode             - 根据城市编码取城市信息
 *   5.  (array)   getCitiesByProvinceCode   - 根据省份编码取城市列表
 *   6.  (array)   getProvincesByCountryCode - 根据国家编码取省份列表
 *   7.  (array)   getCountryByName          - 根据国家名称取国家信息（利用strpos支持模糊查询）
 *   8.  (array)   getProvinceByName         - 根据省份名称取省份信息（利用strpos支持模糊查询）
 *   9.  (array)   getCityByName             - 根据城市名称取城市信息（利用strpos支持模糊查询）
 *   10. (boolean) isExistCountryCode        - 是否存在指定的国家编码
 *   11. (boolean) isExistProvinceCode       - 是否存在指定的省份编码
 *   12. (boolean) isExistCityCode           - 是否存在指定的城市编码
 **/

class TaeRegion {
	private static $regionCode = null;
	/**
	 * 加载地区码数据。
	 *   加载完成的数据存放在 self::$regionCode ，直接取用
	 * @return void
	 **/
	private static function _loadData() {
		if(empty(self::$regionCode)) {
			self::$regionCode = TaeRegionCodeData::getRegionCode();
		}
	}

	/**
	 * 根据地区编码和类型直接读取地区信息
	 * @param int $code 地区编码
	 * @param string $type 地区类型。enum("COUNTRY","PROVINCE","CITY");
	 * @return array $region 地区信息。如果指定地区不存在，或者参数错误，返回空数组。
	 *                       建议取得返回值后，通过 if(empty($region)) 判断后再用
	 **/
	public static function getRegion($code, $type) {
		$_type = strtoupper(trim((string)$type));
		$code = (int)$code;
		if(!in_array($_type,array('COUNTRY','PROVINCE','CITY'))) {
			return array();
		}
		self::_loadData();
		$theRegion =& self::$regionCode[$type][$code];
		if(empty($theRegion) || !is_array($theRegion)) {
			return array();
		}
		return $theRegion;
	}

	/**
	 * 根据地区码取国家信息
	 * @param int $code 国家编码
	 * @return array $region 地区信息。如果指定编码的国家不存在，则返回空数组
	 **/
	public static function getCountryByCode($code) {
		return self::getRegion($code,'COUNTRY');
	}

	/**
	 * 根据省份编码取省份的信息
	 * @param int $code 省份编码
	 * @return array $region 省份信息。如果指定编码的省份不存在，则返回空数组
	 **/
	public static function getProvinceByCode($code) {
		return self::getRegion($code,'PROVINCE');
	}

	/**
	 * 根据城市编码取城市的信息
	 * @param int $code 城市编码
	 * @return array $region 城市信息。如果指定编码的城市不存在，则返回空数组
	 **/
	public static function getCityByCode($code) {
		return self::getRegion($code,'CITY');
	}

	/**
	 * 根据指定的省份编码取该省份下的城市列表
	 * @param int $code 省份编码
	 * @return array $cities 城市列表。如果指定编码的省份不存在，或者该省份下没有城市，则返回空数组
	 **/
	public static function getCitiesByProvinceCode($code) {
		$code = (int) $code;
		// 检查给到的code是否是真实存在的 Province Code
		if(!self::isExistProvinceCode($code)) {
			return array();
		}
		// 遍历城市列表，给到指定省份的城市列表
		self::_loadData();
		$cities = array();
		foreach(self::$regionCode['CITY'] as $_cityCode => $_city) {
			if($_city['ParentCode'] == $code) {
				$cities[$_cityCode] = $_city;
			}
		}
		return $cities;
	}

	/**
	 * 根据指定的国家编码取该国家下的省份列表
	 * @param int $code 国家编码
	 * @return array $provinces 省份列表。如果制定编码的国家不存在，或者该国家下没有对应省份，则返回空数组
	 **/
	public static function getProvincesByCountryCode($code) {
		$code = (int) $code;
		// 检查给到的code是否是真实存在的 Country Code
		if(!self::isExistCountryCode($code)) {
			return array();
		}
		// 遍历省份列表，给到指定国家的身份
		self::_loadData();
		$provinces = array();
		foreach(self::$regionCode['PROVINCE'] as $_provinceCode => $_province) {
			if($_province['ParentCode'] == $code) {
				$provinces[$_provinceCode] = $_province;
			}
		}
		return $provinces;
	}

	/**
	 * 根据地区名关键字取国家信息（支持模糊查询）
	 *   注意!!! 仅返回第一个符合查询条件的国家信息
	 * @param string $keyword 国家名称关键字
	 * @return array $country 国家信息。如果没有搜索到，返回空数组
	 **/
	public static function getCountryByName($keyword) {
		$keyword = trim((string)$keyword);
		if(empty($keyword)) {
			return array();
		}
		self::_loadData();
		foreach(self::$regionCode['COUNTRY'] as $_countryCode => $_country) {
			if(false !== strpos($_country['RegionName'],$keyword)) {
				return $_country;
			}
		}
		return array();
	}

	/**
	 * 根据地区名关键字取省份信息（支持模糊查询）
	 *   注意!!! 仅返回第一个符合查询条件的省份信息
	 * @param string $keyword 省份名称关键字
	 * @return array $province 省份信息。如果没有搜索到，则返回空数组
	 **/
	public static function getProvinceByName($keyword) {
		$keyword = trim((string)$keyword);
		if(empty($keyword)) {
			return array();
		}
		self::_loadData();
		foreach(self::$regionCode['PROVINCE'] as $_provinceCode => $_province) {
			if(false !== strpos($_province['RegionName'],$keyword)) {
				return $_province;
			}
		}
		return array();
	}

	/**
	 * 根据地区名关键字取城市信息（支持模糊查询）
	 *   注意!!! 仅返回第一个符合查询条件的城市信息
	 * @param string $keyword 城市名称关键字
	 * @return array $city 城市信息。如果没有搜索到，则返回空数组
	 **/
	public static function getCityByName($keyword) {
		$keyword = trim((string)$keyword);
		if(empty($keyword)) {
			return array();
		}
		self::_loadData();
		foreach(self::$regionCode['CITY'] as $_cityCode => $_city) {
			if(false !== strpos($_city['RegionName'],$keyword)) {
				return $_city;
			}
		}
		return array();
	}

	/**
	 * 是否存在指定的国家编码
	 * @param int $code 待检验的国家编码
	 * @return boolean $isExists 给定的国家编码真实存在为true；不存在为false
	 **/
	public static function isExistCountryCode($code) {
		self::_loadData();
		$code = (int) $code;
		return array_key_exists($code,self::$regionCode['COUNTRY']);
	}

	/**
	 * 是否存在指定的省份编码
	 * @param int $code 待检验的省份编码
	 * @return boolean $isExists 给定的省份编码真实存在为true；不存在为false
	 **/
	public static function isExistProvinceCode($code) {
		self::_loadData();
		$code = (int) $code;
		return array_key_exists($code,self::$regionCode['PROVINCE']);
	}

	/**
	 * 是否存在指定的城市编码
	 * @param int $code 待检验的城市编码
	 * @return boolean $isExists 给定的城市编码真实存在为true；不存在为flase
	 **/
	public static function isExistCityCode($code) {
		self::_loadData();
		$code = (int) $code;
		return array_key_exists($code,self::$regionCode['CITY']);
	}
}

