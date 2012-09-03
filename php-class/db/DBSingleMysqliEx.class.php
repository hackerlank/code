<?php
require_once 'CacheMemcached.class.php';
require_once 'DBSingleMysqli.class.php';
class DBSingleMysqliEx extends DBSingleMysqli
{
    private $memcached;
    public function __construct($mysqliConf, $memcachedConf)
    {
        parent::__construct($mysqliConf['dbHost'], $mysqliConf['dbUser'], $mysqliConf['dbUserPwd'], $mysqliConf['dbName']);
        $this->memcached = new CacheMemcached($memcachedConf);
    }
    public function getCacheRows($table, $fileds, $option, $key = '', $expiration=60)
    {
        if (empty($key)) {
            $str = $table.$fileds.serialize($option);
            $key  = hash('sha256', $str);
        }
        $data = $this->memcached->get($key);
        if (!$data) {
            $data = $this->getRows($table, $fileds, $option);
            $this->memcached->set($key, $data, $expiration);
        }
var_dump(debug_backtrace());
        return $data;
    }
    public function getCacheRow($table, $fileds, $option, $key = '', $expiration = 60)
    {
        if (empty($key)) {
            $str = $table.$fileds.serialize($option);
            $key = hash('sha256', $str);
        }
        $data = $this->memcached->get($key);
        if (!$data) {
            $data = $this->getRow($table, $filelds, $option);
            $this->memcached->set($key, $data, $expiration);
        }
        return $data;
    }
    public function getCacheOne($table, $fileds, $where, $key = '', $expiration = 60)
    {
        if (empty($key)) {
            $str = $table.$fileds.$where;
            $key = hash('sha256', $str);
        }
        $data = $this->memcached->get($key);
        if (!$data) {
            $data = $this->getOne($table, $fileds, $where);
            $this->memcached->set($key, $data, $expiration);
        }
        return $data;
    }
}
