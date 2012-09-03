<?php
	/**
	@author:shenjian
	@email:xiaoshengeer@gmail.com
	@create:2012-05-09 11:05:51
	@encoding:utf8 sw=4 ts=4
	**/
class CacheMemcached
{
    private static $memcachedInstance;

    private $errLogFile;

    public function __construct($servers = array(array('127.0.0.1','11211')) , $errLogFile = '/tmp/memecached.err.log')
    {
        $this->errLogFile = $errLogFile;
        if (!isset(self::$memcachedInstance)) {
            self::$memcachedInstance = new Memcached();
            self::$memcachedInstance->addServers($servers) || $this->log('addServers error');
        }
    }
    public function set($key, $val, $expiration=0)
    {
        return self::$memcachedInstance->set($key, $val, $expiration);
    }
    public function get($key)
    {
        return self::$memcachedInstance->get($key);
    }
    public function delete($key)
    {
        return self::$memcachedInstance->delete($key);
    }
    public function flush($delay=0)
    {
        return self::$memcachedInstance->flush($delay);
    }
    public function add($key, $val, $expiration=0)
    {
        return self::$memcachedInstance->add($key, $val, $expiration);
    }
    public function increment($key, $offset=1)
    {
        return self::$memcachedInstance->increment($key, $offset);
    }
    public function decrement($key, $offse=1)
    {
        return self::$memcachedInstance->decrement($key, $offset);
    }
    public function log($msg)
    {
        $logMsg = date('Y-m-d H:i:s').'|'.$msg."\n";
        file_put_contents($this->errLogFile, $logMsg, FILE_APPEND);
    }
}
