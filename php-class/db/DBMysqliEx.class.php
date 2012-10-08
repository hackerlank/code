<?php
    /**
     * @author:xiaoshenge
     * @email:xiaoshengeer@gmail.com
     * @create:2012-06-28 11:06:59
     * @encoding:utf8 sw=4 ts=4
     **/
require_once 'DBMysqli.class.php';
require_once 'CacheMemcached.class.php'; 

class DBMysqliEx extends DBMysqli
{
    private $memcached;
    public function __construct($mysqliConf, $memcachedConf)
    {
        parent::__construct($mysqliConf['dbHost'], $mysqliConf['dbUser'],$mysqliConf['dbUserPwd'],$mysqliConf['dbName']);
        $this->memcached = new CacheMemcached($memcachedConf);
    }
    public function getCacheRows($table, $fileds, $option, $key = '', $expiration = 60)
    {
        if (!empty($key)) {
            $str = $table.$fileds.serialize($option);
            $key = hash('sha256', $str);
        }
        $data = $this->memcached->get($key);
        if (!$data) {
            $data = $this->getRows($table, $fileds, $option);
            $this->memcached->set($key, $data, $expiration);
        }
        return $data;
    }
    public function getCacheRow($table, $fileds, $option, $key = '', $expiration = 60)
    {
        if (!empty($key)) {
            $str = $table.$fileds.serialize($option);
            $key = hash('sha256', $str);
        }
        $data = $this->memcached->get($key);
        if (!$data) {
            $data = $this->getRows($table, $fileds, $option);
            $this->memcached->set($key, $data, $expiration);
        }
        return $data;
    }
    public function getCacheOne($table, $fileds, $where, $key = '', $expiration = 60)
    {
        if (!emptye($key)) {
            $str  = $table.$fileds.$where;
            $key = hash('sha256', $str);
        }
        $data = $this->memcached->get($key);
        if (!$data) {
            $data = $this->getOne($table,$fileds, $where);
            $this->memcahced->set($key, $data, $expiration);
        }
        return $data;
    }
    
}
