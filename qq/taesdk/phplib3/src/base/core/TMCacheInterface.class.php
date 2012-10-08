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
 * The interface of Cache
 *
 * @package sdk.lib3.src.base.core
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-1
 */
interface TMCacheInterface{
    /**
     * set cache key-value map
     * @access public
     * @param string $key    the cache key
     * @param string $value  the cache value
     * @param string $expire the expire time
     * @return void
     */
    public function set($key,$value,$expire=0);

    /**
     * set cache alive key-value map
     * @access public
     * @param string $key    the cache key
     * @param string $value  the cache value
     * @param string $expire the expire time
     * @return void
     */
    public function setAlive($key, $alive, $expire);

    /**
     * get cache value by key
     * @access public
     * @param string $key   the cache key
     * @return string       the cached value
     */
    public function get($key);

    /**
     * get alive cache value by key
     * @access public
     * @param string $key   the cache key
     * @return string       the cached value
     */
    public function getAlive($key);

    /**
     * clear alive key-value map in cache
     * @param string $key    the cache key
     * @return void
     */
    public function clearAlive($key);

    /**
     * execute $obj->$function and cache the execution result if $reset is true,or get the value from cache by default key
     * @param string $obj       the object
     * @param string $function  the function which the object call
     * @param string $param     the function params
     * @param string $category  the cache expires way
     * @param string $reset     reset the cache value the default key indicated or not
     * @return mixed
     */
    public function libCached(&$obj, $function, array $param, $namespace, $category=null, $cacheZigzagBase=30, $cacheZigzagMutiple=5, $cacheTimeout=30, $reset=false);
}
