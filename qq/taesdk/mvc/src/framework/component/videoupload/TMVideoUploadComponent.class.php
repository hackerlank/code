<?php

/*
 * ---------------------------------------------------------------------------
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
 * ---------------------------------------------------------------------------
 */

/**
 * TMVideoUploadComponent 视频上传
 *
 * @package components.fileupload.classes
 * @author  gastonwu <gastonwu@tencent.com>
 * @version TMVideoUploadComponent.class.php 2011-08-09 by gastonwu
 * 
 * 上传流程
 */
class TMVideoUploadComponent {

	// 配置文件信息
	private $componentDir; // 控件路径
	private $configPath; // 配置文件路径
	private $_config; // 自定义配置信息
	private static $instances = array();

	/**
	 * 构造函数
	 */
	public function __construct($app = 'videoupload') {
		// 读取配置文件
		$this->componentDir = TMDispatcher::getComponentsDir('videoupload');
		$this->configPath = $this->componentDir . 'config/' . $app . '.yml';
		$this->_loadConfigFile($this->configPath); // 读取配置文件数据
	}

	/**
	 * 获取TMFileUploadComponent当前对象
	 * @return TMFileUploadComponent
	 */
	public static function getInstance($app = 'videoupload') {
		if (!isset(self::$instances [$app])) {
			$class = __CLASS__;
			self::$instances [$app] = new $class($app);
		}

		return self::$instances [$app];
	}

	/**
	 * 按平台组协义，不做url_encode转义
	 * @param array $array
	 * @return string 
	 */
	private function http_build_query($array){
		$line = "";
		foreach($array as $key=>$val){
			$line .= "&$key=$val" ;
		}
		$line = substr($line,1);
		return $line;
	}

	/**
	 * 获取 ticket
	 */
	public function getTicket($input) {
		// 验证用户是否登录
		if (!TMAuthUtils::isLogin()) {
			return json_encode($this->getResult('ERROR_SYSTEM_ISNOT_LOGIN'));
		}
		//标签校验
		if(empty($input['tags']) === false){
			preg_match("/[^\x{4e00}-\x{9fa5}0-9a-zA-Z\ ]+/u", $input['tags'], $match);
			$tagsInclude = empty($match) ? false : true;

			if($tagsInclude){
				$log = new TMLog();
				$log->ll("tags:".$input['tags']);
				$log->ll("match:".print_r($match,true));
				return json_encode($this->getResult('ERROR_TAG_INPUT'));
			}
		}


		// 验证是否在活动期间内
		$openTime = $this->getConfig('date');
		$nowTime = date('Y-m-d H:i:s');

		if ($nowTime < $openTime ['start'] || $nowTime > $openTime ['end']) {
			$ret = $this->getResult('ERROR_SYSTEM_TIMEOUT');
			return json_encode($ret);
		}

		// 获取 ticket

		$log = new TMLog();
		try {            
			TaeCore::taeInit(TaeConstants::UIN, TMAuthUtils::getUin());
			$callInput = array(
					"ticket_id" => 4,
					"input_num" => 1,
					//"input1" => 3,
					"input1" => http_build_query(array(
							"title" => $input["title"],
							"tags" => $input["tags"],
							"cat" => $input["cat"],
							"desc" => $input["desc"],
							"upflag" => $this->getConfig('up_flag'),
							"sizelimit"=>$this->getConfig('size_limit'),
							"appkey"=>$this->getConfig('appkey'),
							)),
					);
			$log->ll("input:".print_r($input,true));
			$log->ll("uin:".TMAuthUtils::getUin());
			$log->ll(print_r($callInput,true));


			$result = TaeCore::taeCall(2302, $callInput);
			$log->ll("result:".$result);

			if (0 == $result ['retcode']) {
				$data ['ticket'] = $result ['ticket'];
				$log = new TMLog();
				$log->ll("ticket-1:".$data['ticket']);
				return json_encode($this->getResult('SUCCESS', $data));
			}

			return json_encode($this->getResult('ERROR_SYSTEM_BUSY'), $result);
		} catch (Exception $e) {
			$log->ll("Exception:".print_r($e->getMessage(),true));
			$result = array("ret_code" => $e->getCode(), "rsp_msg" => $e->getMessage());
			return json_encode($this->getResult('ERROR_SYSTEM_BUSY', $result));
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
		$qq = TMAuthUtils::isLogin() ? TMAuthUtils::getUin() : 0;
		if (!$qq) {
			return $this->getResult('ERROR_SYSTEM_ISNOT_LOGIN');
		}

		// 验证是否在活动期间内
		$openTime = $this->getConfig('date');
		$nowTime = date('Y-m-d H:i:s');

		if ($nowTime < $openTime ['start'] || $nowTime > $openTime ['end']) {
			return $this->getResult('ERROR_SYSTEM_TIMEOUT');
		}

		$priverKey = $this->getConfig('private_key');
		$actId = TMConfig::get('tams_id');
		$checkStr = $qq . $actId . $fileId . $priverKey . $localUrl . $storeUrl;
		$checkCode = md5($checkStr);

		if ($verifyCode == $checkCode) {
			return $this->getResult('SUCCESS');
		}

		return $this->getResult('ERROR_FAIL');
	}

	/**
	 * 获取投票的配置
	 * @param string $path 配置文件的路径
	 */
	private function _loadConfigFile($path) {
		if (!file_exists($path)) {
			$content = json_encode(array("code" => 99, "message" => "component app config file ($path) does not exist"));
			throw new TMVoteComponentException($content);
		}

		$this->_config = TMBasicConfigHandle::getInstance()->execute($path);
	}

	/**
	 * 获取参数配置
	 * @param unknown_type $param
	 */
	public function getConfig($param) {
		if (array_key_exists($param, $this->_config)) {
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
		$result = $this->getConfig('result');

		if (!array_key_exists($key, $result)) {
			return json_encode($result ['ERROR_SYSTEM_BUSY']);
		}

		$ret = $result [$key];
		$ret ['data'] = $data;

		return $ret;
	}

}
