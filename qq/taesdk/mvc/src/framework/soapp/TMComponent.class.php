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
 * The soapp core class
 *
 * @package sdk.mvc.src.framework.soapp
 * @author  gastonwu <gastonwu@tencent.com>
 * @version TMComponent.class.php 2010-08-03 by gastonwu
 */
class TMComponent{
    /**
     * @var Component
     */
    private static $instance;
    /**
     * @var TMWebRequest
     */
    protected $request;
    /**
     * @var TMWebResponse
     */
    protected $response;
    protected $methodMaskTag = "_for";
    protected $configFile = "";
    /**
     * 用于保存component.yml的配置项
     * @var array
     */
    protected $config;
    protected $uri;
    protected $logicMethodList = array();
    protected $afterFilterList = array();

    protected $tagServiceSplit = ".";

    protected $logicInstanceMap = array();

    protected $currentLogicItem = array();

    //当前运行的class.method
    protected $currentLogicItemKeyName = "";

    protected $currentParamData = array();

    protected $exceptionView = "";

    /**
     * 得到一个TMComponent的实例
     * @static
     * @return TMComponent
     */
    public static function getInstance(){
        $obj = self::$instance;
        if(is_object($obj) === false){
            self::$instance = new TMComponent();
            self::$instance->initialize();
        }
        return self::$instance;
    }
    
    /**
     * 初始化变量，没有初始化request，只有在需要使用request的才去初始化
     */
    protected function initialize()
    {
        $this->logicInstanceMap['component'] = $this;
        register_shutdown_function(array(&$this, 'registerShutdown'));
        $this->response = new TMWebResponse();
    }

    /**
     * 指定shutdown的操作
     */
    public function registerShutdown(){
        if('on' !== $this->config['component']['debug']['Show'])
        {
            return;
        }
    }
    
    /**
     * 返回http请求
     * @return TMWebRequest
     */
    public function getRequest()
    {
        if($this->request == null)
        {
            $this->request = new TMWebRequest();
        }
        return $this->request;
    }

    /**
     * 返回http请求
     * @return TMWebResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * 返回当前运行逻辑的名字，比如CResponse.render
     * @return string
     */
    public function getCurrentLogicItemKeyName(){
        return $this->currentLogicItemKeyName;
    }

    /**
     * 解析soapp配置文件
     * @param string $yamlFilename
     */
    public function loadConfig($yamlFilename=""){
        $yamlFile = "";
        if(empty($yamlFilename)){
            $yamlFile = ROOT_PATH."config/soapp.yml";
        }else{
            $yamlFile = ROOT_PATH.$yamlFilename;
        }
        $this->configFile = $yamlFile;
        $yaml = TMYamlCacher::getInstance()->execute($yamlFile);

        if(is_array($yaml) === false){
            $this->debug("load $yamlFile error.");
            exit;
        }
        $this->config = $yaml;
        
        if(isset($_ENV['SERVER_TYPE']) &&  $_ENV['SERVER_TYPE']== "test"){
            if(is_dir(ROOT_PATH."config/subcomp/")){
                $this->loadSubConfig(ROOT_PATH."config/subcomp/", $subConfigArray);
                
                foreach($subConfigArray as $subConfig)
                {
                	$this->config["action"] = array_merge($this->config["action"], $subConfig["action"]);
                }
            }
        }
    }
    
    /**
     * 获取子文件夹中的配置
     * @param string $dir
     * @param array $subConfigArray
     */
    private function loadSubConfig($dir, & $subConfigArray)
    {
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if (is_dir($dir.$file) && $file != '.' && $file != '..') {
                    $this->loadSubConfig($dir.$file."/", $subConfigArray);
                } elseif (preg_match('/^[a-zA-Z0-9]+\.yml$/', $file)) {
                    $subConfigArray[] = TMYamlCacher::getInstance()->execute($dir.$file);
                }
            }
            closedir($handle);
        }
    }

    /**
     * 返回配置数组
     * @return array
     */
    public function getConfig(){
        return $this->config;
    }
    
    /**
     * 判断当前的uri是否有对应的配置
     * @return boolean
     */
    public function isMatchURI(){
        return isset($this->config['action'][$this->uri]);
    }

    /**
     * 获取当前请求的URI
     * @param string $uri
     */
    public function initURI($uri){ 
    	$baseUrl = TMConfig::get("base_url");
    	preg_match("/^http:\/\/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?\/(.*)\/$/",$baseUrl,$matches);
    	if(isset($matches[2])){
    	   $base = $matches[2];
    	   $uri = str_replace("/".$base,"",$uri);
    	}
    	$uri = (substr($uri,-1) == "/" && strlen($uri) != 1) ? substr($uri, 0, -1) : $uri;
        $this->uri = $uri;
    }

    /**
     * 打印debug信息
     * @param string $msg
     */
    public function debug($msg){
        TMComponentLog::getInstance()->debug($msg);
    }

    /**
     * 打印trace信息
     * @param string $msg
     */
    public function trace($msg){
        TMComponentLog::getInstance()->trace($msg);
    }

    /**
     * 初始化一个uri对应需要执行的组件服务的列表
     * @param string $uri
     */
    protected function logicMethodListInit($uri){
        if(isset($this->config['action'][$uri]) === false){
            $this->trace("not component config match uri:{$uri}");
            throw new TM404Exception("not component config match uri:{$uri}");
        }
        $this->trace(str_repeat("-",30)."{$uri}".str_repeat("-",30));

        $logicItemList = $this->config['action'][$uri];
        $logicItemList = empty($logicItemList) ? array() : $logicItemList;
        $list = array();
        foreach($logicItemList as $key=>$val){
            $list[][$key]=$val;
        }
        $this->logicMethodList = $list;
    }

    /**
     * 将全局filter需要执行的组件服务加入到执行列表中
     */
    protected function logicMethodListUriFilter(){
        $logicItemIncludeFileterList = array();
        $logicItemIncludeAfterFilterList = array();

        $logicItemList = $this->logicMethodList;

        //url filter - before
        $urlBeforeFilter = $this->config['component']['UrlFilterBefore'];
        $urlAfterFilter = $this->config['component']['UrlFilterAfter'];

        if(empty($urlBeforeFilter) === false){
            foreach($urlBeforeFilter as $beforeKey=>$beforeVal){
                $logicItemIncludeFileterList[][$beforeKey]=$beforeVal;
            }
        }
        //origin
        foreach($logicItemList as $pos=>$logicItem){
            $logicItemIncludeFileterList[]=$logicItem;
        }
        //url filter after
        if(empty($urlAfterFilter) === false){
            foreach($urlAfterFilter as $afterKey=>$afterVal){
                $logicItemIncludeAfterFilterList[][$afterKey]=$afterVal;
            }
        }
        $this->logicMethodList = $logicItemIncludeFileterList;
        $this->afterFilterList = $logicItemIncludeAfterFilterList;
    }

    /**
     * 输出response中的内容，包括头信息
     */
    public function output()
    {
        $content = $this->response->getContent();
        $length = strlen($content);

        $this->response->setHttpHeader("Content-Length", $length);

        $this->response->send();
    }

    /**
     * 依次运行组件服务列表中的调用
     * @param string $logicItemList 传入组件服务列表，默认执行逻辑方法列表
     */
    protected function logicMethodListLoopRun($logicItemList = ''){
        if(!is_array($logicItemList) && empty($logicItemList))
        {
            $logicItemList = $this->logicMethodList;
        }
        foreach($logicItemList as $pos=>$logicItem){
            foreach($logicItem as $key=>$val){
                $this->currentLogicItem[$key]=$val;
                $this->currentLogicItemKeyName = $key;
                if($key == "view")
                {
                	$baseComponent = new TMBaseComponent();
                    $baseComponent->set("view", $val);
                    continue;
                }
                if($key == "exceptionView")
                {
                    $this->exceptionView = $val;
                    continue;
                }
                $return = $this->logicMethodRun($key,$val);
                if(is_null($return) === false){
                    $this->response->setContent($return);
                    return;
                }
                unset($this->currentLogicItem[$key]);
            }
        }
    }

    /**
     * 运行after filter中的组件服务
     */
    protected function logicAfterFilterLoopRun()
    {
        $this->logicMethodListLoopRun($this->afterFilterList);
    }

    /**
     * 执行soapp请求
     */
    public function run(){
        try{
            $this->logicMethodListInit($this->uri);
            $this->logicMethodListUriFilter();
            $this->logicMethodListLoopRun();
            $this->logicAfterFilterLoopRun();
        }
        catch(TMMysqlException $me)
        {
            TransactionService::rollback();
            $this->handleException($me);
        }
        catch(TMException $te)
        {
            $this->handleException($te);
        }
    }

    /**
     * 系统进行异常的最外层处理
     * @param TMException $te
     */
    protected function handleException($te)
    {
        if(!empty($this->exceptionView))
        {
            $tpl = $this->exceptionView;
        }
        else{
            $tpl = "error/syserror.php";
        }
        $this->response->setContent($te->handle($tpl));
    }

    /**
     * 判断需要依赖的关系是否成立
     * @param mixed $logicObject   当前执行的组件类  
     * @param array $paramsConfig        当前传入组件服务的$data
     * @return boolean
     */
    protected function isDependOnSuccess($logicObject,$paramsConfig){
        if (isset($paramsConfig['_dependOn'])) {
            $_dependOnTag = '_dependOn';
        } elseif (isset($paramsConfig['_DependOn'])) {
            $_dependOnTag = '_DependOn';
        } else {
            return true;
        }
        
        $data = $logicObject->parseFromParams($paramsConfig[$_dependOnTag]);
        $actual = $paramsConfig[$_dependOnTag]['actual'];
        //debug info
        $debugLine1 = is_array($actual) ? $actual[0]."('".$actual[1]."')" : $actual;
        $debugLine2 = $data['expected'] === $data['actual'] ? "===" : "!==";
        $this->debug(">depend\tactual.config:$debugLine1");
        $this->debug(">depend\t(expected:{$data['expected']}{$debugLine2}actual:{$data['actual']})");
        //return
        if($data['expected'] === $data['actual']){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 去掉method mask的真实函数
     *
     * @param string $classMethod
     * @return string
     */
    public function getRealClassMethod($classMethod){
        $pos = strpos($classMethod,$this->methodMaskTag);
        if($pos === false){
            return $classMethod;
        }
        $classMethod = substr($classMethod,0,$pos);
        return $classMethod;
    }

    /**
     * 得到mask method的后缀
     * @param string $classMethod
     * @return string
     */
    public function getMaskMethodPostfix($classMethod){
        $pos = strpos($classMethod,$this->methodMaskTag);
        if($pos === false){
            return '';
        }
        $tagLen = strlen($this->methodMaskTag);
        $postfix = substr($classMethod,$pos+$tagLen,strlen($classMethod));
        return $postfix;
    }

    /**
     * 执行组件服务
     * @param  string $logicMethodDefine
     * @param  array $paramsConfigs
     * @return string $content
     */
    protected function logicMethodRun($logicMethodDefine,$paramsConfig){
        $realClassMethod = $this->getRealClassMethod($logicMethodDefine);
        list($className,$methodName) = explode($this->tagServiceSplit, $realClassMethod);
        if(isset($paramsConfig['_Debug'])){
            $this->debug("paramsConfig:".print_r($paramsConfig,true));
            $this->debug("\$_REQUEST:".print_r($_REQUEST,true));
            $this->debug("\$_PROPERTY:".print_r(TMBaseComponent::getData(),true));
        }
        $logicObject = $this->logicInstanceRunMap($className);
        //DependOn check
        if($this->isDependOnSuccess($logicObject,$paramsConfig) === false){
            $this->trace(str_repeat("-",15)."".$logicMethodDefine."\t[depend fail]");
            return;
        }else{
            $this->trace(str_repeat("-",15)."".$logicMethodDefine."");
        }
        $data = $paramsConfig;
        $this->currentParamData = $data;

        //处理_for函数，set value的情况
        $logicObject->setCurPostfix4set($this->getMaskMethodPostfix($logicMethodDefine));
        if(isset($_ENV['SERVER_TYPE']) &&  $_ENV['SERVER_TYPE']== "test"){
            $timer = TMTimerManager::getTimer("$logicMethodDefine Execute");
            $return = $logicObject->$methodName($data);
            $timer->addTime();
        }else{
            $return = $logicObject->$methodName($data);
        }
        return $return;
    }

    /**
     * 获得一个组件类的实例
     * @param string $serviceClassName
     * @return mixed
     */
    protected function logicInstanceRunMap($serviceClassName){
        if(empty($serviceClassName)){
            return;
        }
        
        $className = ucfirst($serviceClassName);

        if(isset($this->logicInstanceMap[$serviceClassName])){
            return $this->logicInstanceMap[$serviceClassName];
        }

        $this->logicInstanceMap[$serviceClassName] = new $className();

        return $this->logicInstanceMap[$serviceClassName];
    }
}
