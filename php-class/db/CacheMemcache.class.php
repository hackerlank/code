<?php
    /**
     * @author:xiaoshenge
     * @email:xiaoshengeer@gmail.com
     * @create:2012-06-28 11:06:01
     * @encoding:utf8 sw=4 ts=4
     **/
class CacheMemcache
{
    private static $memecacheInstance;

    private $errLogFile;
    
    public function __construct($serves = array(array('127.0.0.1','11211')), $errLogFile="/tmp/memcache.err.log")
    {
        $this->errlogFile = $errLogFile;
        if (!self::$memcacheInstance) {
            selef::$memcacheInstance = new Memcache();
            foreach($servers as $server)
                self::$memcacheInstance->addServer($server) || $this->log("addServer error: $server[0]:$server[1]");
        }
    }
    public function add($key, $val, $expiration=0)
    {
        return selef::$memcacheInstance->add($key, $val, $expiration);
    }
    public function get($key)
    {
        return selef::$memcacheInstance->get($key);
    }
    public function set($key, $val, $expiration=0)
    {
        return self::$memcacheInstance->set($key, $val, $expiration=0);
    }
    public function flush()
    {
        return self::$memcacheInstance->flush();
    }
    public function increment($key, $offset=1)
    {
        return self::$memcacheInstance->increment($key, $offset);
    }
    public function decrement($key, $offset=1)
    {
        return self::memcahceInstance->decrement($key, $offset);
    }   
    public function log($msg)
    {
        $errMsg = date('Y-m-d H:i:s')."|$msg\n";
        file_put_contents($this->errLogFile, $errMsg);
    }
}
