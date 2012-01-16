<?php
define('ROOT_PATH', realpath(dirname(__FILE__) . '/../') . '/');
#在提交代码到svn之前，将4,5行注释去掉，，6,7行加上注释
#define('LIB_PATH', '/usr/local/taesdk/1.1/phplib3/src/');
#define('FRAMEWORK_PATH', '/usr/local/taesdk/1.1/mvc/src/framework/');
define('LIB_PATH', ROOT_PATH.'../taesdk/phplib3/src/');
define('FRAMEWORK_PATH', ROOT_PATH.'../taesdk/mvc/src/framework/');
define('CACHE_PATH', ROOT_PATH.'cache/');

set_include_path(get_include_path() . PATH_SEPARATOR .LIB_PATH. PATH_SEPARATOR. FRAMEWORK_PATH);

require_once 'base/core/TMAutoload.class.php';

TMAutoload::getInstance()
    ->setDirs(array(ROOT_PATH."library/", ROOT_PATH."controllers/", ROOT_PATH."components/", LIB_PATH, FRAMEWORK_PATH))
    ->setSavePath(CACHE_PATH.'autoload/')->execute();
    
try{
    TMConfig::initialize();
    TMDispatcher::createInstance()->dispatch();
}catch(TMException $ae){
    header("HTTP/1.1 404 Not Found");
    echo $ae->getMessage();
}
?>