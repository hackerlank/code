<?php
	/**
	@author:shenjian
	@email:xiaoshengeer@gmail.com
	@create:2012-05-09 08:05:56
	@encoding:utf8 sw=4 ts=4
	**/
error_reporting(E_ALL);
ini_set("display_errors","on");

require_once 'db/DBSingleMysqli.class.php';
$testDB = new DBSingleMysqli('localhost', 'root', 'root', 'test');
/*
$res = $testDB->getRow('ip2lists', 'startip','id=5');
print_r($res);
var_dump($testDB);
$mmcmsDB = new DBSingleMysqli('localhost', 'root', 'root', 'mmcms');
var_dump($mmcmsDB);
$res2 = $mmcmsDB->getRow('producttype');
print_r($res2);
$res3 = $testDB->getRow('ip2lists');
print_r($res3);
*/
/*
for ($i = 0; $i < 10000; $i++) {
    error_log(memory_get_peak_usage()."\n", 3, '/tmp/php.memory.log');
    $res = $testDB->getRows('ip2lists');
    error_log(memory_get_peak_usage()."\n", 3, '/tmp/php.memory.log');
}
*/
$res = $testDB->insert('documents',array('title'=>'xiaoshenge','content'=>'test'));
var_dump($res);
print_r($testDB->getInsertId());
