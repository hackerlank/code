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
 * Manage APC Cache
 *
 * Usage:
 * 请参考TMMemCacheMgr的usage
 *
 * @package sdk.lib3.src.base.core
 * @author Salon Zhao <salonzhao@tencent.com>
 * @version 2008-12-15
 * @version 2009-09-07 Lynk Li <lynkli@tencent.com>
 */
class TMLibAPCMgr implements TMCacheInterface
{
    const DATA_EXPIRE_DIFF = 3600;
    private $nameSpace;
    private $expire;
    private static $_instance;

    /**
     * set namespace and expire time
     *
     * @param int $expire the expire time
     * @param string $nameSpace    the namespace
     * @return void
     */
    protected function __construct( $nameSpace, $expire = 0 )
    {
        $this->nameSpace = $nameSpace;
        $this->expire = $expire;
    }

    /**
     * 获得一个APC实例化
     *
     * @param string $nameSpace
     * @param int $expire
     * @return TMLibAPCMgr
     */
    public static function getInstance( $nameSpace, $expire = 0 ) {
        if(empty(self::$_instance)) {
            $class_name = __CLASS__;
            self::$_instance = new $class_name($nameSpace, $expire);
        }
        return self::$_instance;
    }

    /**
     * cache the key-value map in apc and set the expire time
     *
     * @access public
     * @param string $key the key of the map
     * @param mixed $value the value of the map
     * @param int $expire the expire time of the map in apc
     * @return void
     */
    public function set($key, $value, $expire=0)
    {
        if ($expire == 0)
        {
            $expire = $this->expire;
        }
        $key = $this->nameSpace . "_" . $key;
        apc_store($key, $value, $expire);
    }

    /**
     * set the alive flag and expire time of the key-value map in apc
     *
     * @access public
     * @param string $key the key of the key-value map
     * @param mixed $alive the alive flag of the key-value map
     * @param int $expire the expire time
     * @return void
     */
    public function setAlive($key, $alive, $expire)
    {
        $key = "__ALIVE__" . $this->nameSpace . "_" . $key;
        apc_store($key, $alive, $expire);
    }

    /**
     * get value from apc by key
     *
     * @access public
     * @param string $key the key of the value in apc
     * @return mixed
     */
    public function get($key)
    {
        $key = $this->nameSpace . "_" . $key;
        return apc_fetch($key);
    }

    /**
     * get alive flag of the key-value map in apc by the key
     *
     * @access public
     * @param string $key the key of the key-value map
     * @return mixed
     */
    public function getAlive($key)
    {
        $key = "__ALIVE__" . $this->nameSpace . "_" . $key;
        return apc_fetch($key);
    }

    /**
     * delete the key-value map from apc by the key
     *
     * @access public
     * @param string $key the key of the key-value map in apc
     * @param boolean $alive
     * @return void
     */
    public function clear($key, $alive=true)
    {
        apc_delete($this->nameSpace . "_" . $key);
        if ($alive)
        {
            $this->clearAlive($key);//apc_delete("__ALIVE__" . $this->nameSpace . "_" . $key);
        }
    }

    /**
     * delete the alive flag of the key-value map in apc by the key
     *
     * @access public
     * @param string $key the key of the key-value map
     * @return void
     */
    public function clearAlive($key)
    {
        apc_delete("__ALIVE__" . $this->nameSpace . "_" . $key);
    }

    /**
     * execute $obj->$function and cache the execution result if $reset is true,or get the value from cache by default key
     *
     * @access public
     * @param object $obj Class object
     * @param string $function Name of function
     * @param array $param Parameter array of the function
     * @param string $namespace
     * @param mixed $category if is_numeric, timeout seconds, else category of timeout
     * @param int $cacheZigzagBase
     * @param int $cacheZigzagMutiple
     * @param int $cacheTimeout
     * @param bool $reset force cache reset
     * @param array $options other settings
     * @return mixed function result
     */
    public function libCached(&$obj, $function, array $param, $namespace, $category=null, $cacheZigzagBase=30, $cacheZigzagMutiple=5, $cacheTimeout=30, $reset=false, $options=array())
    {
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
            TMDebugUtils::debugLog('apc cache miss: ' . $key);

            if ($zigzag && $cacheZigzagBase)
            {
                $max_multiple = $cacheZigzagMutiple;
                $timeout = $cacheZigzagBase * rand(1, $max_multiple ? $max_multiple : 10);
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
            TMDebugUtils::debugLog('apc cache hit: ' . $key);
            return unserialize($cached);
        }

        return null;
    }
}