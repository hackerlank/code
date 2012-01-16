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
if(!class_exists('TMAutoload')){
	$dirname = dirname(__FILE__);
	require_once $dirname.'/TaeConstants.class.php';
	require_once $dirname.'/JSON.class.php';
	require_once $dirname.'/TaeException.class.php';
}
/**
 * TAE调用核心类
 *
 * @package sdk.lib3.src.tae.core
 * @author  happysonxu<happysonxu@tencent.com>
 * @version TaeCore.class.php 2011-6-16 by happysonxu
 */
class TaeCore{

    private static $_inited = false;
    private static $_config = array(TaeConstants::TIMEOUT=>3,TaeConstants::CONNECT_TIMEOUT=>1,TaeConstants::SERVER_PORT=>26000);
    private static $_header = array();
    private static $_headerField = array(TaeConstants::UIN,TaeConstants::USER_IP,TaeConstants::ACT_ID,TaeConstants::VERSION);
    private static $_curl = null;//curl句柄
    private static $_servers = null;//设置的server对象数组
    private static $_currServer = null;
    private static $_excludeList = null;

    /**
     * 进行TAE调用的初始化操作，用来初始化多个变量
     * @param string $key
     * @param mixed $value
     */
    public static function taeInit($key,$value)
    {
        self::$_config[$key] = $value;
        //如果是header字段，加入到header数组
        if(in_array($key,self::$_headerField))
        {
            self::$_header[TaeConstants::HEADER_PREFIX.$key] = $value;
        }
    	if($key==TaeConstants::SERVER_IP||$key==TaeConstants::SERVER_PORT){
        	self::$_servers = array(array('host'=>self::getConfig(TaeConstants::SERVER_IP),'port'=>self::getConfig(TaeConstants::SERVER_PORT)));
        }
        
        if($key==TaeConstants::SERVER_LIST){
        	self::$_servers = $value;
        }
    }

    /**
     * 远程调用TAE服务
     * @param string $commmand 指令码
     * @param array $parameter
     * @return array
     */
    public static function taeCall($commmand,$parameter = array())
    {
        //为参数增加body前缀
        $data = array();
        foreach ($parameter as $key => $value)
        {
            $data[TaeConstants::BODY_PREFIX.$key] = $value;
        }
        //加入命令号
        self::$_header[TaeConstants::HEADER_PREFIX."cmd_id"] = $commmand;
        //与header数组进行合并
        $data = array_merge(self::$_header,$data);
        //生成json
        $jsonObj = new JSON();
        $json = $jsonObj->serialize($data);
        self::log("request:$json");
        //使用http方式调用后台
        $ret = self::getJson($commmand,$json);
        self::log("response:$ret");
        //解码
        $result = $jsonObj->unserialize($ret);
        if(empty($result))
        {
            self::throwException("Fail to decode:$ret",TaeConstants::ERR_DECODE_FAIL);
        }
        if($result[TaeConstants::BODY_PREFIX."logic_err"]!=0)//调用产生非逻辑出错，抛出异常
        {
            self::throwException($result[TaeConstants::BODY_PREFIX."rspmsg"],$result[TaeConstants::BODY_PREFIX."retcode"],$json,$result);
        }

        return self::getBody($result);
    }

    /**
     * 远程调用fastcgi相关服务
     * @param string $uri
     * @param array $params
     * @param string $vhost
     * @return array
     */
    public static function fastCgiCall($uri, $params = array(), $vhost = null)
    {
        $curl = new TMCurl($uri);

        if(!empty($vhost)){
            $curl->setVHost($vhost);
        }
         
        if(!isset($params[TaeConstants::ACT_ID]))
        {
            $params[TaeConstants::ACT_ID] = self::$_config[TaeConstants::ACT_ID];
        }

        $ret = $curl->sendByGet($params);

        $jsonObj = new JSON();

        $result = $jsonObj->unserialize($ret);

        if(empty($result))
        {
            self::throwException("Fail to decode:$ret",TaeConstants::ERR_DECODE_FAIL);
        }
        if($result[TaeConstants::BODY_PREFIX."logic_err"]!=0)//调用产生非逻辑出错，抛出异常
        {
            self::throwException($result[TaeConstants::BODY_PREFIX."rspmsg"],$result[TaeConstants::BODY_PREFIX."retcode"],$json,$result);
        }

        return self::getBody($result);
    }
    
    /**
     * 远程调用weibo相关服务
     * @param string $uri
     * @param mixed $param
     * @param boolean $isPost
     * @param boolean $needArrayParam
     * @return string $content
     */
    public static function weiboCall($uri, $param, $isPost = false, $needArrayParam = false)
    {
        //先去读取uri对应的路由表，来判断此uri是走什么后台
        //如果是走tae的后台，则调用fastCgiCall
        //如果是走starjiang的接口，则调用http curl接口
        if(empty($uri))
        {
            self::throwException("Uri is empty");
        }
        
        if($uri[0] != "/")
        {
            $uri = "/".$uri;
        }
        
        $routeArray = TMConfig::get("weibo", "tae");
        if(empty($routeArray))
        {
            $routeArray = array();
        }
        
        //路由判断
        $serverType = TaeConstants::WEIBO_HTTP_SERVER;
        foreach($routeArray as $route => $routeServer)
        {
            if(preg_match("/$route/", $uri))
            {
                $serverType = $routeServer;
                break;
            }
        }
                    
        if($serverType == TaeConstants::WEIBO_TAE_SERVER)
        {
            //call tae fastcgi
        }else{
            //curl weibo http server
            $weiboServers = TMConfig::get("weibo", $serverType);
            if(empty($weiboServers))
            {
                self::log("no available weibo server!");
                self::throwException("no available weibo server!",TaeConstants::ERR_NO_AVAILABLE_SERVER);
            }
            
            foreach ($weiboServers as $weiboServer)
            {
                try{
                    $host = $weiboServer["host"];
                    $vhost = $weiboServer["vhost"];
                    
                    $url = "http://$host/innerapi$uri";
                    $curl = new TMCurl($url);
                    if(!empty($vhost)){
                        $curl->setVHost($vhost);
                    }
                    
                    if(is_array($param) && !$needArrayParam)
                    {
                        $paramStr = TMUtil::handleParameter($param, "&");
                    }
                    else{
                        $paramStr = $param;
                    }
                    
                    $ret = $curl->send($paramStr, $isPost);
                    
                    return $ret;
                }catch(TMRemoteException $tre)
                {
                    //do nothing 避免异常抛出
                }
            }
            
            self::log("no available weibo server!");
            self::throwException("no available weibo server!",TaeConstants::ERR_NO_AVAILABLE_SERVER);
        }
    }

    /**
     * 获取配置
     * @param string $type
     * @return mixed
     */
    public static function getConfig($type)
    {
        return self::$_config[$type];
    }

    public static function getHeader($type)
    {
        $result = array();
        $data = self::$_header;
        
        return $data[TaeConstants::HEADER_PREFIX.$type];
    }
    
    /**
     * 取出数组中的body部分。并且去掉body前缀
     *
     * @param array $data
     * @return array
     */
    private static function getBody($data)
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            if(substr($key,0,5)==TaeConstants::BODY_PREFIX)
            {
                $result[substr($key,5)] = $value;
            }
        }
        return $result;
    }

    /**
     * 获取CURL远程调用结果
     * @param string $cmd
     * @param string $reqJson
     * @return string
     */
    private function getJson($cmd,$reqJson)
    {
    	$server = self::getServer();
	    while (!empty($server)){
	        $ch = self::getCurl($cmd,$server);
	        curl_setopt($ch,CURLOPT_POSTFIELDS,$reqJson);
	        self::log("connecting".$server['host'].':'.$server['port']." for cmd $cmd");
	        $ret = curl_exec($ch);
	        $errno = curl_errno($ch);
	        if($errno!=0){//出错
	        	$errmsg = curl_error($ch);
	        	self::log("fail to connect ".$server['host'].':'.$server['port'].":$errmsg");
	        	//获取下一个可用server ip
	        	$server = self::getServer($server);
	        	continue;
	        }
	        //正常返回结果
        	return $ret;
    	}
    	//所有server遍历后无结果，抛异常
    	self::log("no available server!");
		self::throwException("no available server!",TaeConstants::ERR_NO_AVAILABLE_SERVER);    	
    }

    /**
     * 初始化CURL调用句柄
     * @param string $cmd
     * @return curl
     */
    private static function getCurl($cmd,$server)
    {
        if(self::$_curl===null)
        {
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_HEADER,false);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,self::getConfig(TaeConstants::CONNECT_TIMEOUT));
            curl_setopt($ch,CURLOPT_TIMEOUT,self::getConfig(TaeConstants::TIMEOUT));
            self::$_curl = $ch;
        }
        curl_setopt(self::$_curl,CURLOPT_PORT,$server['port']);
        $server_name = TaeConstants::getCommandServer($cmd);
        $server_ip = $server['host'];
        $url = "http://$server_ip/$server_name/$cmd";
        curl_setopt(self::$_curl,CURLOPT_URL,$url);
        return self::$_curl;
    }
    
    /**
     * 获取下一个可用的ip
     * @param string $excludeIP 已用的ip
     * @return string
     */
    private static function getServer($exclude = null){

    	if(self::$_excludeList == null){
    		self::$_excludeList = array();
    	}
    	if($exclude!=null){
    		self::$_excludeList[] = $exclude;
    	}
    	if(self::$_currServer==null||$exclude!=null){
    		if(empty(self::$_servers)){
    			self::throwException('server config not inited',TaeConstants::ERR_NOT_INITED);
    		}
    		//计算可用数组
    		$list = array_udiff(self::$_servers,self::$_excludeList,array("TaeCore","serverDiff"));
    		//如果无可用，返回空
    		if(count($list)==0){
    			return '';
    		}
    		self::$_currServer = $list[rand(0,count($list)-1)];
    	}
    	return self::$_currServer;
    }

    /**
     * 抛出异常
     * @param string $msg
     * @param int $ret_code
     * @param string $request
     * @param string $response
     * @throw TaeException
     */
    private static function throwException($msg,$ret_code,$request='',$response='')
    {
        $exception = new TaeException($msg);
        $exception->setRetCode($ret_code);
        $exception->setRequest($request);
        $exception->setResponse($response);
        throw $exception;
    }
    
    private static function serverDiff($a,$b)
    {
    	if($a['host']==$b['host']&&$a['port']==$b['port']){
    		return 0;
    	}
    	return 1;
    }

    /**
     * 记录日志
     * @param string $message
     */
    private static function log($message)
    {
        if(@class_exists("TMDebugUtils"))
        {
        	TMDebugUtils::debugLog($message);
        }
    }
}