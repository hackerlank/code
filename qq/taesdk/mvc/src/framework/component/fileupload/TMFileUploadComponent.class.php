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
 * TMFileUploadComponent 文件上传
 *
 * @package components.fileupload.classes
 * @author  nikoniu <nikoniu@tencent.com>
 * @version TMFileUploadComponent.class.php 2011-08-09 by nikoniu
 * 
 * 上传流程
 */

class TMFileUploadComponent {
	
	// 配置文件信息
	private $componentDir; // 控件路径
	private $configPath; // 配置文件路径
	private $_config; // 自定义配置信息
	private static $instances = array ();
	
	/**
	 * 构造函数
	 */
	public function __construct($app = 'fileupload') {
		// 读取配置文件
		$this->componentDir = TMDispatcher::getComponentsDir ( 'fileupload' );
		$this->configPath = $this->componentDir . 'config/' . $app . '.yml';
		$this->_loadConfigFile ( $this->configPath ); // 读取配置文件数据
	}
	
	/**
	 * 获取TMFileUploadComponent当前对象
	 * @return TMFileUploadComponent
	 */
	public static function getInstance($app = 'fileupload') {
		if (! isset ( self::$instances [$app] )) {
			$class = __CLASS__;
			self::$instances [$app] = new $class ( $app );
		}
		
		return self::$instances [$app];
	}
	
	/**
	 * 获取 ticket
	 */
	public function getTicket() {
		// 验证用户是否登录
		if (! TMAuthUtils::isLogin ()) {
			return json_encode ( $this->getResult ( 'ERROR_SYSTEM_ISNOT_LOGIN' ) );
		}
		
		// 验证是否在活动期间内
		$openTime = $this->getConfig ( 'date' );
		$nowTime = date ( 'Y-m-d H:i:s' );
		
		if ($nowTime < $openTime ['start'] || $nowTime > $openTime ['end']) {
			$ret = $this->getResult ( 'ERROR_SYSTEM_TIMEOUT' );
			return json_encode ( $ret );
		}
		
		// 获取 ticket
		try {
			TaeCore::taeInit ( TaeConstants::UIN, TMAuthUtils::getUin () );
			TaeCore::taeInit ( TaeConstants::ACT_ID, TMConfig::get ( 'tams_id' ) );
			
			$result = TaeCore::taeCall ( 2302, array ("ticket_id" => 2, "input_num" => 1, "input1" => 1 ) );
			
			if (0 == $result ['retcode']) {
				$data ['ticket'] = $result ['ticket'];
				return json_encode ( $this->getResult ( 'SUCCESS', $data ) );
			}
			
			return json_encode ( $this->getResult ( 'ERROR_SYSTEM_BUSY' ), $result );
		} catch ( Exception $e ) {
			$result = array ("ret_code" => $e->getCode (), "rsp_msg" => $e->getMessage () );
			return json_encode ( $this->getResult ( 'ERROR_SYSTEM_BUSY', $result ) );
		}
	}
	
	/**
	 * 验证返回码是否正确
	 * @param $fileId #文件服务器返回ID
	 * @param $localUrl #临时URL，本地URL
	 * @param @storeUrl #公司服务器URL
	 * @param @verifyCode #验证码
	 * @return JSON $result
	 */
	public function verify($fileId, $localUrl, $storeUrl, $verifyCode) {
		
		// 验证用户是否登录
		$qq = TMAuthUtils::isLogin () ? TMAuthUtils::getUin () : 0;
		if (! $qq) {
			return $this->getResult ( 'ERROR_SYSTEM_ISNOT_LOGIN' );
		}
		
		// 验证是否在活动期间内
		$openTime = $this->getConfig ( 'date' );
		$nowTime = date ( 'Y-m-d H:i:s' );
		
		if ($nowTime < $openTime ['start'] || $nowTime > $openTime ['end']) {
			return $this->getResult ( 'ERROR_SYSTEM_TIMEOUT' );
		}
		
		$priverKey = $this->getConfig ( 'private_key' );
		$actId = TMConfig::get ( 'tams_id' );
		$checkStr = $qq . $actId . $fileId . $priverKey . $localUrl . $storeUrl;
		$checkCode = md5 ( $checkStr );
		
		if ($verifyCode == $checkCode) {
			return $this->getResult ( 'SUCCESS' );
		}
		
		return $this->getResult ( 'ERROR_FAIL' );
	}
	
	/**
	 * 获取图片缩略图URL
	 * @param unknown_type $imgUrl
	 * @param unknown_type $size
	 */
	public static function getThumbUrl($imgUrl, $size) {
		if (IN_ARRAY ( $size, array ('100', '200', '400', '800' ) )) {
			return substr_replace ( $imgUrl, '/' . $size, strripos ( $imgUrl, '/' ) );
		}
		return $imgUrl;
	}
	
	/**
	 * 获取投票的配置
	 * @param string $path 配置文件的路径
	 */
	private function _loadConfigFile($path) {
		if (! file_exists ( $path )) {
			$content = json_encode ( array ("code" => 99, "message" => "component app config file ($path) does not exist" ) );
			throw new TMVoteComponentException ( $content );
		}
		
		$this->_config = TMBasicConfigHandle::getInstance ()->execute ( $path );
	}
	
	/**
	 * 获取参数配置
	 * @param unknown_type $param
	 */
	public function getConfig($param) {
		if (array_key_exists ( $param, $this->_config )) {
			return $this->_config [$param];
		}
		
		return null;
	}
	
	/**
	 * 获取返回数据
	 * @param string $key
	 * @param array $data
	 */
	public function getResult($key, $data = null) {
		$result = $this->getConfig ( 'result' );
		
		if (! array_key_exists ( $key, $result )) {
			return json_encode ( $result ['ERROR_SYSTEM_BUSY'] );
		}
		
		$ret = $result [$key];
		$ret ['data'] = $data;
		
		return $ret;
	}
}