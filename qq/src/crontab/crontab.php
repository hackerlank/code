<?php
/***********************************
如果在beta下，需要增加以下代码
$_ENV['SERVER_TYPE'] = 'beta';
************************************/
define('ROOT_PATH', realpath(dirname(__FILE__) . '/../') . '/');
define('LIB_PATH', '/usr/local/taesdk/1.1/phplib3/src/');
define('FRAMEWORK_PATH', '/usr/local/taesdk/1.1/mvc/src/framework/');
define('CACHE_PATH', ROOT_PATH.'cache/');

set_include_path(get_include_path() . PATH_SEPARATOR .LIB_PATH. PATH_SEPARATOR. FRAMEWORK_PATH);

require_once 'base/core/TMAutoload.class.php';

TMAutoload::getInstance()->setDirs(array(ROOT_PATH, LIB_PATH, FRAMEWORK_PATH))
    ->setSavePath(CACHE_PATH.'autoload/')->execute();

$log = new TMLog();
TMDebugUtils::addLogger($log);
TMException::addLogger($log);	
	
try{
    TMConfig::initialize();
    
    //TODO 完成业务逻辑
	//如果需要使用到TAE的调用，请开启下面的注释
    //TMTaeInitFilter::taeInit();
    
    //在使用TaeMPService发送虚拟物品的时候，需要在sendItem之前去调用TaeCore::taeInit(TaeConstants::UIN, $qq)
    //将接收虚拟物品的QQ号码设置到接口调用中
}catch(TMException $ae){

}
?>