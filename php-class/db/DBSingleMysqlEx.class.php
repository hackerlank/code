<?php
    /**
     * @author:xiaoshenge
     * @email:xiaoshengeer@gmail.com
     * @create:2012-06-28 14:06:13
     * @encoding:utf8 sw=4 ts=4
     **/
require_once 'DBSingleMysql.class.php';
require_once 'CacheMemcache.class.php';

class DBSingleMysqlEx extends DBSingleMysql
{
    private $memcache;
    
    public function __construct($mysqlConf, $memcacheConf)
    {
        parent::__construct($mysqlConf['dbHost'], $mysqlConf['dbUser'], $mysqlConf['dbUserPwd'], $mysqlConf['dbName']);
        $this->memcache  = new CacheMemcache($memcacheConf);
    }
    public function getCacheRows($table, $fileds, $option, $key = '', $expiration = 60)
    {
        if (empty($key)) {
            $str = $table.$fileds.serialize($option);
            $key = hash('sha256', $str);
        }
        $data = $this->memcahce->get($key);
        if (!$data) {
            $data = $this->getRows($table, $fileds, $option);
            $this->memcache->set($key, $data, $expiration);
        }
        return $data;
    }
    public function getCacheRow($table, $fileds, $option, $key = '', $expiration)
    {
        if (empty($key)) {
            $str = $table.$fileds.serialize($option);
            $key = hash('sha256', $str);
        }
        $data = $this->memcache->get($key);
        if (!$data) {
            $data = $this->getRow($table, $fileds, $option);
            $this->memcahce->set($key, $data, $expiration);
        }
        return $data;
    }
    public function getCacheOne($table, $fileds, $where, $key = '', $expiration)
    {
        if (empty($key)) {
            $str = $table.$fileds.$where;
            $key = hash('sha256',$str);
        }
        $data = $this->memcache->get($key);
        if (!$data) {
            $data = $this->getOne($table, $fileds, $where);
            $this->memcache->set($key, $data, $expiration);
        }
        return $data;
    }
}
