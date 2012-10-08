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
  * The base component
  *
  * @package sdk.mvc.src.framework.soapp
  * @author  gastonwu <gastonwu@tencent.com>
  * @version TMBaseComponent.class.php 2010-08-03 by gastonwu
  * @version TMBaseComponent.class.php 2010-08-17 by ianzhang
  */
class TMBaseComponent {
    /**
     * @var array
     */
    protected static $data = array();
    
    /**
     * 当前运行的classMethod的mask后缀
     *
     * @var string
     */
    protected $curPostfix4set;
    
    /**
     * @var TMService4Component
     **/
    protected $sqlService;
    
    protected $tagServiceSplit=".";

    /**
     * 在日志中记录debug信息
     * @param string $msg
     */
    public function debug($msg){
        TMComponentLog::getInstance()->debug($msg);
    }

    /**
     * 在日志中记录trace信息
     * @param string $msg
     */
    public function trace($msg){
        TMComponentLog::getInstance()->trace($msg);
    }

    /**
     * 返回组件之间共享的变量容器
     * @return array
     */
    public static function getData(){
        return self::$data;
    }

    /**
     * 设置当前组件服务_for之后的后缀
     * @param mixed $postfix4set
     */
    public function setCurPostfix4set($postfix4set){
        $this->curPostfix4set = $postfix4set;
    }

    /**
     * 得到当前组件服务_for之后的后缀
     * @return mixed $postfix4set
     */
    public function getCurPostfix4set(){
        return $this->curPostfix4set;
    }

    /**
     * 得到数据库服务的对象
     * @return TMService4Component
     */
    public function getSqlServiceInstance(){
        if(is_object($this->sqlService) === false){
            $this->sqlService = new TMService4Component();
        }

        return $this->sqlService;
    }

    /**
     * 设置组件类共享容器的键值对
     * @param string $key
     * @param mixed $val
     */
    public function set($key,$val){
        $key = $key.$this->getCurPostfix4set();
        self::$data[$key]=$val;
    }

    /**
     * 得到组件类共享容器中的键的值
     * @param string $key
     * @param mixed $val
     * @return mixed
     */
    public function get($key){
        return isset(self::$data[$key]) ? self::$data[$key] : null;
    }

    /**
     * 设置组件类共享容器的键值对
     * @param string $key
     * @param mixed $val
     */
    protected function __set($key, $val){
        $this->set($key, $val);
    }
    
    /**
     * 得到组件类共享容器中的键的值
     * @param string $key
     * @param mixed $val
     * @return mixed
     */
    protected function __get($key)
    {
        return $this->get($key);
    }
    
    /**
     * 渲染view
     * @param array $data
     * @return string
     */
    public function render($data){
        //判断是否需要监测
        $response = TMComponent::getInstance()->getResponse();
        if($data['needTrack'] !== FALSE)
        {
            $response->setNeedTrack();
        }
        
        //处理数据
        $renderView = $data['view'];
        if(empty($renderView))
        {
            $renderView = self::$data["view"];
        }
        $vars = $data;
        if(!is_array($vars)){
        	$vars = array();
        }
        if(is_array(self::$data)){
            $component = TMComponent::getInstance();
            $config = $component->getConfig();
            $valueKeysName = $config['component']['debug']['ValueKeysName'];
            $valueKeysName = empty($valueKeysName) ? '_ValueKeys' : $valueKeysName;
            self::$data[$valueKeysName] = array_keys(self::$data);
            $vars = array_merge(self::$data, $vars);
        }
        
        self::debug("render file:".$renderView);
        $view = new TMView();
        if(!isset($data["layout"])){
            $content = $view->render($vars, $renderView);
        }
        else{
            $content = $view->render($vars, $renderView, $data["layout"]);    
        }
        
        return $content;
    }
    
    /**
     * 渲染ajax页面内容
     * @param array $data     input array data
     * @return the response content
     */
    protected function renderAjax($data)
    {
        $response = TMComponent::getInstance()->getResponse();
        $response->setAjax();
        $data["needTrack"] = false;
        return $this->render($data);
    }

    /**
     * 以text/xml头格式渲染模板
     * @param array $data     input array data
     * @return the response content
     */
    protected function renderXml($data)
    {
        $response = TMComponent::getInstance()->getResponse();
        $response->setHttpHeader('Content-Type','text/xml');
        return $this->render($data);
    }

    /**
     * 以application/x-javascript头格式渲染模板
     * @param array $data     input array data
     * @return the response content
     */
    protected function renderJs($data)
    {
        $response = TMComponent::getInstance()->getResponse();
        $response->setHttpHeader('Content-Type','application/x-javascript');
        return $this->render($data);
    }
    
    /**
     * 直接打印数据
     * @param array $data
     * @return string
     */
    public function renderMustMessage($data){
        return print_r($data,true);
    }
    
    /**
     * @name 渲染Html页面(include 监控的js code)
     * @param array $data
     * @return string
     */
    public function renderPage($data){
        $content = self::render($data);
        return $content;
    }

    /**
     * 渲染页面，如果没有message则不渲染
     * @param array $data
     * @return string
     */
    public function renderMessage($data){
        $component = TMComponent::getInstance();
        //将render属性存入hash中
        $currentLogicItemName = $component->getCurrentLogicItemKeyName();
        if($_ENV['SERVER_TYPE'] != "test"){
            unset($data['comment']);//todo 去掉注释
        }
        //self::$data['renderMessage'][$currentLogicItemName] = $data;
        
        //render
        $renderMessageView = $data['view'];
        if(empty($renderMessageView))
        {
            $renderMessageView = self::$data["view"];
        }
        if(empty($data["message"])) return;
        $this->debug("render file:".$renderMessageView);
        foreach($data as $key=>$val){
            if(is_array($val)){
                $val = $this->parseFromParams($val);
                $val = $this->arrayParamBind($val);
            }
            $data[$key] = $val;
        }
        $vars = array_merge(self::$data, $data);
                
        $tmView = new TMView();
        $content = $tmView->render($vars, $renderMessageView);
        
        return $content;
    }
    
    /**
     * 调用sprintf函数，将字符串数组做变量部分替换，如果传入的是字符串，则直接返回
     * @param array $arrayParam
     * @return string
     */
    public function arrayParamBind($arrayParam){
        if(empty($arrayParam)) {
        	return "";
        }
        if(is_string($arrayParam)){
            return $arrayParam;
        }
        if(count($arrayParam) <= 1 ) {
        	return $arrayParam[0];
        }
        $stringPart = call_user_func_array('sprintf',$arrayParam);
        return $stringPart;
    }

    /**
     * 调用sprintf函数，将字符串数组做变量部分替换并进行sql注入检查，如果传入的是字符串，则直接返回
     * @param array $sqlParam
     * @return string
     */
    public function sqlParamBind($sqlParam){
        if(empty($sqlParam)){
        	return "";
        }
        if(is_string($sqlParam)){
        	return $sqlParam;
        }
        if(count($sqlParam) <= 1 ){
        	return $sqlParam[0];
        }
        $sqlService = $this->getSqlServiceInstance();
        for($i=1,$total=count($sqlParam);$i<$total;$i++){
            $sqlParam[$i] = $sqlService->getConnect()->formatString($sqlParam[$i]);
        }
        $sqlPart = call_user_func_array('sprintf',$sqlParam);
        return $sqlPart;
    } 
    
    /**
     * 将传入yml参数进行解析，比如将[_ENV,loginQQ]解析成_ENV('loginQQ')的调用
     * @param array $params
     * @param TMComponentValueGetter $valueGetter
     * 
     * @return array $data
     */
    public function parseFromParams($params, $valueGetter = null){
        if(is_array($params) === false){
        	return $params;
        }
        $data= array();
        if(empty($valueGetter)){
            $valueGetter = TMComponentValueGetter::getInstance();
        }
        foreach($params as $key=>$val){
            $data[$key] = $this->parseParamItem($val, $valueGetter);
        }
        return $data;
    }

    /**
     * 将传入yml参数进行解析，比如将[_ENV,loginQQ]解析成_ENV('loginQQ')的调用,并检查必填的key是否指定
     * @param array $params
     * @param array $mustKeys
     * @param TMComponentValueGetter $valueGetter
     * 
     * @return array $data
     */
    public function parseMustParams($params, $mustKeys, $valueGetter = null){
    	foreach($mustKeys as $mustKey)
    	{
    	    if(isset($params[$mustKey]) === false) {
                throw new TMConfigException("parameter '$mustKey' can not be null.");
            }
    	}
        $data = $this->parseFromParams($params, $valueGetter);
        foreach($mustKeys as $mustKey){
            if(empty($data[$mustKey])){
                $this->trace("parameter '$mustKey' is empty.");
            }
        }
        return $data;
    }
    
    /**
     * 解析yml传入单个参数的值
     * @param mixed $paramItem
     * @param TMComponentValueGetter $valueGetter
     * 
     * @return string
     */
    public function parseParamItem($paramItem, $valueGetter = null){
        $return = "";
        $val = $paramItem;
        if($this->isParseArray($val) === false){
            $return = $val;
            return $return;
        }
        $callback = isset($val[0]) ? trim($val[0]) : 0;
        if($callback[0] === "_"){
            unset($val[0]);
	        if(empty($valueGetter)){
	            $valueGetter = TMComponentValueGetter::getInstance();
	        }
            $return = $valueGetter->$callback($val[1]);
            $return = (empty($val[2]) === false ) && empty($return) ? $val[2] : $return;
        }else if(strpos($callback,$this->tagServiceSplit) > -1){
            list($className,$methodName) = explode($this->tagServiceSplit, $callback);

            $logicObject = TMComponent::getInstance()->logicInstanceRunMap($className);
            unset($val[0]);
            $newVal = call_user_func_array(
	            array(
		            &$logicObject,
		            $methodName
	            ),
	            $val
            );
            $return = $newVal;
        }

        return $return;
    }

    /**
     * 检查传入参数数组的值是否符合框架规范
     * @param array $array
     * @return boolean
     */
    public function isParseArray($array){
        if(is_array($array) === false) {
        	return false;
        }
        reset($array);
        
        $key = key($array);
        if($key !== 0) {
        	return false;
        }
        if($array[0][0] == "_"){ 
        	return true;
        }
        if(strpos($array[0],".") > -1) {
        	return true;
        }
        return false;
    }

    /**
     * 返回一个列表
     * @param string $className  调用这个方法具体的组件类名
     * @param string $functionName  调用这个方法的组件服务名
     * @param string $tableName     需要访问的表名
     * @param array $data           从yml传入soapp的参数数组
     */
    public function commonList41Table($className,$functionName,$tableName,$data){
        $sqlService = $this->getSqlServiceInstance();
        //condOption
        $condOption = $this->parseFromParams($data['conditionOption']);
        $sqlCondPart = $this->sqlParamBind($condOption);

        //sortOption
        $sortOption = $this->parseFromParams($data['sortOption']);
        $sqlSortPart = $this->sqlParamBind($sortOption);

        //query total
        $sql = "select count(*) as total from $tableName {$sqlCondPart}";
        $rows = $sqlService->query($sql);
        $total = $rows[0]['total'];
        $this->set($className."_".$functionName."_total",$total);

        $mustArray = array(
            "itemsOfPage", "pageNums", "baseUrl" 
        );
        $paginationData = $this->parseMustParams($data['pagination'], $mustArray);
        $pagination = $this->pageinationHandle($className, $functionName, $paginationData, $total);

        //query list
        $offset = ($pagination->getCurrentPage() - 1) * $paginationData['itemsOfPage'];
        $sql = "select * from $tableName {$sqlCondPart} {$sqlSortPart} limit {$offset},{$paginationData['itemsOfPage']}";
        $rows = $sqlService->query($sql);
        $this->set($className."_".$functionName."_list",$rows);
    }

    /**
     * 返回一个搜索列表
     * @param string $className  调用这个方法具体的组件类名
     * @param string $functionName  调用这个方法的组件服务名
     * @param string $tableName     需要访问的表名
     * @param array $data           从yml传入soapp的参数数组
     */
    public function commonSearch41Table($className,$functionName,$tableName,$data){
        $sqlService = $this->getSqlServiceInstance();
        //select
        $select = $this->sqlParamBind($this->parseFromParams($data['select']));
        $where = $this->sqlParamBind($this->parseFromParams($data['where']));
        $order = $this->sqlParamBind($this->parseFromParams($data['order']));
        $total = $this->sqlParamBind($this->parseFromParams($data['total']));

        $totalQuery = "$total $where";
        //total
        $rows = $sqlService->query($totalQuery);
        $total = $rows[0][0];
        $this->set($className."_".$functionName."_total",$total);

        //page
        $mustArray = array(
            "itemsOfPage", "pageNums", "baseUrl" 
        );
        $paginationData = $this->parseMustParams($data['pagination'], $mustArray);
        $pagination = $this->pageinationHandle($className, $functionName, $paginationData, $total);

        //query list
        $offset = ($pagination->getCurrentPage() - 1) * $paginationData['itemsOfPage'];
        $limit = " limit {$offset},{$paginationData['itemsOfPage']}";
        //$sql = "select * from $tableName {$sqlCondPart} {$sqlSortPart} limit {$offset},{$PaginationData['ItemsOfPage']}";
        $selectQuery = "$select $where $order $limit ";
        $this->debug($selectQuery);
        $rows = $sqlService->query($selectQuery);
        $this->set($className."_".$functionName."_list",$rows);
    }
    
    /**
     * 处理分页操作
     * @param string $className  调用这个方法具体的组件类名
     * @param string $functionName  调用这个方法的组件服务名
     * @param array $paginationData   从yml传入soapp的pagination参数数组  
     * @param int $total              总页数
     */
    protected function pageinationHandle($className, $functionName, $paginationData, $total)
    {
    	//page
        $pageKey = !empty($paginationData['pageKey']) ? $paginationData['pageKey']: "page";
        $totalPage = ceil($total / $paginationData['itemsOfPage']);
        if($totalPage == 0)
        {
        	$totalPage = 1;
        }
        $pagination = new CPagination();
        $baseUrlOption = $this->parseFromParams($paginationData['baseUrl']);
        //做一个sprintf的操作
        $baseUrl = $this->arrayParamBind($baseUrlOption);
        $pagination->setBaseUrl($baseUrl);
        $pagination->setItemOfPage($paginationData['itemsOfPage']);
        $pagination->setCurrentPage(TMComponent::getInstance()->getRequest()->getParameter($pageKey));
        //用于google式分页栏的配置
        $pagination->setPagesOfTotal($paginationData['pageNums']);
        $pagination->setTotalPage($totalPage);
        $pagination->genData();
        
        $template = !empty($paginationData['template']) ? $paginationData['template']: "component/page.include.php";
        $pageHtml = $pagination->includeTemplate($template);
        $this->set($className."_".$functionName."_pagination",$pagination);
        $this->set($className."_".$functionName."_paginationHtml",$pageHtml);
        
        return $pagination;
    }

    /**
     * @name 按照约定的参数列表，以大小写不敏感的方式处理参数列表，返回符合命名规则的数组
     * @author simonkuang <simonkuang@tencent.com>
     * @since 2010-09-06
     * @param array $data 一般为从YAML取得的配置数组
     * @param array $acceptedParameterList 接受的参数列表。请使用规整驼峰命名参数
     * @return array $data 处理之后的参数数组
     * @example
     * class CAward extends TMBaseComonent {
     *     public function isOverPerAwardPerQQ($data) {
     *         $acceptedParameterList = array(
     *             'awardLimitMap',
     *             'onOverLimit',
     *         );
     *         $data = $this->_caseInsensitiveParameter($data,$acceptedParameterList);
     * 
     *         ......
     *     }
     *
     *     ......
     * }
     */
    protected function _caseInsensitiveParameter($data, $acceptedParameterList) {
        $acceptedParameterList_ = array();
        foreach($acceptedParameterList as $_value) {
            $acceptedParameterList_[strtolower($_value)] = $_value;
        }
        foreach($data as $_key => $_value) {
            if(!is_string($_key)) { // 不是数字的键值，直接空过
                continue;
            }
            $_key = strtolower($_key);
            if(!empty($acceptedParameterList) && !array_key_exists($_key, $acceptedParameterList_)) {
                // 提炼成方法之后，就不方便再记log。
                // 因为LOG中 __CLASS__ 和 __FUNCTION__ 全变成本方法的类名和方法名
                //$this->debug("Unknown parameter: {$_key}");
                continue;
            }
            $data[$acceptedParameterList_[$_key]] = $_value;
        }
        unset($acceptedParameterList_);
        return $data;
    }
}