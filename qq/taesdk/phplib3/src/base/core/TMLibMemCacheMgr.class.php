<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2008 BY  TENCENT  CORPORATION.  ALL RIGHTS
 *     RESERVED.   NO  PART  OF THIS PROGRAM  OR  PUBLICATION  MAY
 *     BE  REPRODUCED,   TRANSMITTED,   TRANSCRIBED,   STORED  IN  A
 *     RETRIEVAL SYSTEM, OR TRANSLATED INTO ANY LANGUAGE OR COMPUTER
 *     LANGUAGE IN ANY FORM OR BY ANY MEANS, ELECTRONIC, MECHANICAL,
 *     MAGNETIC,  OPTICAL,  CHEMICAL, MANUAL, OR OTHERWISE,  WITHOUT
 *     THE PRIOR WRITTEN PERMISSION OF :
 *
 *                        TENCENT  CORPORATION
 *
 *       Advertising Platform R&D Team, Advertising Platform & Products
 *       Tencent Ltd.
 *---------------------------------------------------------------------------
 */

/**
 * The class which manage the Memcache
 *
 * Usage:
 * <code>
 * $sql = "select * from Tbl_User";
 * $service = new TMService();
 * //到缓存中获取列表，如果没有设置或者已经过期，则执行SQL获取并缓存30秒；如果缓存存在并且没有过期，则直接返回缓存中的结果
 * $results = TMMemCacheMgr::getInstance()->cached($service, "query", array($sql), 30);
 * //同上，只不过缓存时间是应用的TMConfig::$_cacheCategories['default']对应的时间
 * $results = TMMemCacheMgr::getInstance()->cached($service, "query", array($sql), 'default');
 * //同上，只不过缓存时间是TMConfig::CACHE_ZIGZAG_BASE到TMConfig::CACHE_ZIGZAG_BASE*TMConfig::CACHE_ZIGZAG_MULTIPLE之间的一个随机数
 * $results = TMMemCacheMgr::getInstance()->cached($service, "query", array($sql), 'zigzag');
 *
 * $results = $service->query($sql);
 * //把查询结果集存入key为'userListQuery'.TMConfig::TAMS_ID的memcache中，缓存有效期30秒
 * //如果已经有相同key的缓存存在，那么将用新的值替换老的值，并且缓存有效期将刷新为30秒
 * TMMemCacheMgr::getInstance()->set('userListQuery'.TMConfig::TAMS_ID, $results, 30);
 * //把查询结果集存入key为'userListQuery'.TMConfig::TAMS_ID的memcache中，缓存有效期30秒
 * //如果已经有相同key的缓存存在，则操作失败，返回值$boolean为false
 * //如果不存在相同key的缓存，则操作成功，返回值$boolean为true
 * $boolean = TMMemCacheMgr::getInstance()->add('userListQuery'.TMConfig::TAMS_ID, $results, 30);
 * //从缓存中获取key为'userListQuery'.TMConfig::TAMS_ID的缓存，如果缓存不存在则返回false，如果存在则返回缓存的值
 * $results = TMMemCacheMgr::getInstance()->get('userListQuery'.TMConfig::TAMS_ID);
 *
 * //删除key为'userListQuery'.TMConfig::TAMS_ID的缓存，同时删除key为'__ALIVE__userListQuery'.TMConfig::TAMS_ID的缓存，后者是前者是否过期的标志
 * TMMemCacheMgr::getInstance()->clear('userListQuery'.TMConfig::TAMS_ID);
 * </code>
 *
 * @package sdk.lib3.src.base.core
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-1
 * @version 2009-09-07 Lynk Li <lynkli@tencent.com>
 */
class TMLibMemCacheMgr implements TMCacheInterface
{
    /**
     * @var int DATA_EXPIRE_DIFF 数据与Alive状态过期的时间差，即数据将延迟 DATA_EXPIRE_DIFF 秒以后过期
     */
    const DATA_EXPIRE_DIFF = 3600;
    /**
     * @var Memcache $cache Memcache对象
     */
    private $cache;
    /**
     * @var TMMemCacheMgr $instance
     */
    private static $instance;

    private $isEnable, $isPersistent;

    const error = "There are some problems in cache system, please check it.";

    /**
     * get a instance of TMMemCache Mgr,create one if not exist  获取当前TMMemCacheMgr对象
     *
     * @access public
     * @param boolean $isEnable
     * @param boolean $isPersistent
     * @param array $configServers
     * @return TMMemCacheMgr
     */
    public static function getInstance($isEnable, $isPersistent, $configServers)
    {
        if(self::$instance == null)
        {
            $class = __CLASS__;
            self::$instance = new $class($isEnable, $isPersistent, $configServers);
        }

        return self::$instance;
    }

    /**
     * 构造函数
     *
     * @param boolean $isEnable
     * @param boolean $isPersistent
     * @param array $configServers
     */
    protected function __construct($isEnable, $isPersistent, $configServers)
    {
        $this->isEnable = $isEnable;
        $this->isPersistent = $isPersistent;
        if ($isEnable)
        {
            $this->cache = new Memcache();

            $persistent = $isPersistent;

            $servers = $configServers;
            foreach ($servers as $server)
            {
                $host = $server["host"];
                $port = empty($server["port"]) ? 11211 : (int) $server["port"];
                $this->cache->addServer($host, $port, $persistent);
            }
        }
    }

    /**
     * close the connection to the memcache server
     *
     * @access public
     */
    public function __destruct() {
        if ($this->isEnable)
        {
            $persistent = $this->isPersistent;

            if (!$persistent)
            {
                $this->cache->close();
            }
        }
    }

    /**
     * Add the key-value map in the cache
     *
     * @access public
     * @param string $key  the key of the memcache
     * @param mix $value  the value, it could be any structure
     * @param int $expire  the lifetime of cache, if you set the value as zero, the cache will be never expired.(Unit: second)
     * @return boolean
     */
    public function add($key, $value, $expire = 0) {
        if (!$this->cache)
        {
            return false;
        }
        return $this->cache->add($key, $value, 0, $expire);
    }

    /**
     * Increment numeric item's value
     *
     * @param string $key
     * @param int $offset
     */
    public function increment($key, $offset=1) {
        if (!$this->cache)
        {
            return false;
        }
        return $this->cache->increment($key, $offset);
    }

    /**
     * set the key-value map in cache
     *
     * @access public
     * @param string $key the key of the memcache
     * @param mixed $value the value, it could be any structure
     * @param int $expire the lifetime of cache, if you set the value as zero, the cache will be never expired.(Unit: second)
     * @return void
     */
    public function set($key, $value, $expire = 0) {
        if (!$this->cache)
        {
            return ;
        }
        $this->cache->set($key, $value, 0, $expire);
    }

    /**
     * set the alive flag in cache
     *
     * @access public
     * @param string $key the key of the memcache
     * @param boolean $alive is alive
     * @param int $expire the lifetime of cache, if you set the value as zero, the cache will be never expired.(Unit: second)
     * @return void
     */
    public function setAlive($key, $alive, $expire)
    {
        if (!$this->cache)
        {
            return ;
        }
        $this->cache->set("__ALIVE__" . $key, $alive, 0, $expire);
    }

    /**
     * get the cache value by key
     *
     * @access public
     * @param string $key the key indicates the value
     * @return mixed the value indicated by the key
     */
    public function get($key)
    {
        if (!$this->cache)
        {
            return null;
        }
        return $this->cache->get($key);
    }

    /**
     * get the alive flag by key
     *
     * @access public
     * @param string $key the key indicates the value
     * @return mixed the value indicated by the key
     */
    public function getAlive($key)
    {
        if (!$this->cache)
        {
            return null;
        }
        return $this->cache->get("__ALIVE__" . $key);
    }

    /**
     * clear key-value map and alive flag in cache
     *
     * @access public
     * @param string $key    memcache key
     * @param boolean $alive
     * @return void
     */
    public function clear($key, $alive=true)
    {
        if (!$this->cache)
        {
            return ;
        }
        $this->cache->delete($key);
        if ($alive)
        {
            $this->clearAlive($key);
        }
    }

    /**
     * clear the alive flag in cache
     *
     * @access public
     * @param  string    $key    memcache key
     * @return void
     */
    public function clearAlive($key)
    {
        if (!$this->cache)
        {
            return ;
        }
        $this->cache->delete("__ALIVE__" . $key);
    }

    /**
     * execute $obj->$function and cache the execution result if $reset is true,or get the value from cache by default key
     *
     * Usage:
     * <code>
     * $sql = "select * from Tbl_User";
     * $service = new TMService();
     * //到缓存中获取列表，如果没有设置或者已经过期，则执行SQL获取并缓存30秒；如果缓存存在并且没有过期，则直接返回缓存中的结果
     * $results = TMMemCacheMgr::getInstance()->cached($service, "query", array($sql), 30);
     * //同上，只不过缓存时间是应用的T-MConfig::$_cacheCategories['default']对应的时间
     * $results = TMMemCacheMgr::getInstance()->cached($service, "query", array($sql), 'default');
     * //同上，只不过缓存时间是TMConfig::CACHE_ZIGZAG_BASE到TMConfig::CACHE_ZIGZAG_BASE*TMConfig::CACHE_ZIGZAG_MULTIPLE之间的一个随机数
     * $results = TMMemCacheMgr::getInstance()->cached($service, "query", array($sql), 'zigzag');
     * </code>
     * @access public
     * @param object $obj Class object
     * @param string $function Name of function
     * @param array $param Parameter array of the function
     * @param string $namespace
     * @param int|string $category if is_numeric, timeout seconds, else category of timeout
     * @param int $cacheZigzagBase
     * @param int $cacheZigzagMutiple
     * @param int $cacheTimeout
     * @param bool $reset force cache reset
     * @param array $options other settings
     * @return mixed function result
     */
    public function libCached(&$obj, $function, array $param, $namespace, $category=null, $cacheZigzagBase=30, $cacheZigzagMutiple=5, $cacheTimeout=30, $reset=false, $options=array())
    {
        if (!$this->isEnable)
        {
            try
            {
                return call_user_func_array(array(&$obj, $function), $param);
            }
            catch (Exception $ex)
            {
                return null;
            }
        }

        $zigzag = false;
        if ($category == 'zigzag')
        {
            $zigzag = true;
            $category = null;
        }
        $category = isset($category) ? $category : 'default';

        //生成关键字
        if (!empty($options['key']))
        {
            $key = $options['key'];
        }
        else
        {
            $key = $namespace . "_" . get_class($obj) . "_{$function}_" . md5(serialize($param));
        }

        $alive = false;
        //如果没有指定重新获取数据，则判断缓存是否过期
        if (!$reset)
        {
            $alive = $this->getAlive($key);
        }

        //如果参数设置为重置，或者已经过期，则重新设置数据
        $result = null;
        if ($reset || empty($alive))
        {
            TMDebugUtils::debugLog('memcache miss: ' . $key);

            if ($zigzag && $cacheZigzagBase)
            {
                $max_multiple = $cacheZigzagMutiple;
                $timeout = !$cacheZigzagBase * rand(1, $max_multiple ? $max_multiple : 10);
            }
            else if (is_numeric($category))
            {
                $timeout = (int) $category;
            }
            else
            {
                $timeout = $cacheTimeout;
            }

            $this->setAlive($key, true, $timeout);
            try
            {
                $result = call_user_func_array(array(&$obj, $function), $param);
            }
            catch (Exception $ex)
            {
                $this->clearAlive($key);
                return null;
            }
            $this->set($key, serialize($result), $timeout + self::DATA_EXPIRE_DIFF);
        }

        //如果已经执行取得结果
        if ($result !== null)
        {
            return $result;
        }

        //从缓存读取结果
        $cached = $this->get($key);
        if (!empty($cached))
        {
            TMDebugUtils::debugLog('memcache hit: ' . $key);
            return unserialize($cached);
        }

        return null;
    }

    /**
     * get the current status of the memcache server
     *
     * @access public
     */
    public function stat()
    {
        if ($this->isEnable)
        {
            $extendStats = $this->cache->getExtendedStats();
        }
        else
        {
            $extendStats = array();
        }

        print_r($extendStats);
    }

    /**
     * decrement numeric item's value
     *
     * @param string $key
     * @param int $offset
     */
    public function decrement($key, $offset=1) {
        if (!$this->cache)
        {
            return false;
        }
        return $this->cache->decrement($key, $offset);
    }

}
