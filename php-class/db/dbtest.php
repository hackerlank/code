<?php
    /**
     * @author:xiaoshenge
     * @email:xiaoshengeer@gmail.com
     * @create:2012-06-28 15:06:13
     * @encoding:utf8 sw=4 ts=4
     **/
require_once 'DBSingleMysqliEx.class.php';

$db = new DBSingleMysqliEx(array('dbHost'=>'127.0.0.1','dbUser'=>'root', 'dbUserPwd'=>'root','dbName'=>'test'),array(array('127.0.0.1','11211')));

        
$res = $db->getCacheRows('ip2lists','*', array('limit'=>10), 'iplists', 600,55);
//var_dump($res);

$res2 = $db->getCacheRows('iplists', '*', array('limit'=>10));
//var_dump($res2);

