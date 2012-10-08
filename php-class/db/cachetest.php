<?php
    /**
     * @author:xiaoshenge
     * @email:xiaoshengeer@gmail.com
     * @create:2012-06-28 09:06:53
     * @encoding:utf8 sw=4 ts=4
     **/
require_once 'CacheMemcached.class.php';

$memcached = new CacheMemcached(array(array('127.0.0.1','11211')));

$res = $memcached->add('name','xiaoshenge');
var_dump($res);


$res1 = $memcached->add('name','xiaoshenge1');
var_dump($res);
