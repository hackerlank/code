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
 * TMMemCacheMgr
 * memcache管理类
 *
 * @package sdk.mvc.src.framework.cache
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMMemCacheMgr.class.php 2010-12-27 by ianzhang    
 */
class TMMemCacheMgr extends TMLibMemCacheMgr{
    const CACHE_ZIGZAG_BASE = 30;
    const CACHE_ZIGZAG_MULTIPLE = 5;

    protected static $_cacheCategories = array(
        'default'    => 30,
        'config'    => 3600
        );

    private static $_instance;

    /**
     * Get cache time out
     * @param string $category
     * @return int
     */
    protected static function cacheTimeout($category)
    {
        $categories = self::$_cacheCategories;
        if (empty($category) || !isset($categories[$category]))
            $category = 'default';
        return $categories[$category];
    }

    /**
     * 构造函数
     * @return void
     */
    protected function __construct() {
        $isEnable = TMConfig::get("memcache","enable");
        $isPersistent = TMConfig::get("memcache","persistent");
        $configServers = TMConfig::get("memcache","server");
        parent::__construct($isEnable, $isPersistent, $configServers);
    }

    /**
     * 得到一个memcache实例
     * @return TMMemCacheMgr
     */
    public static function getInstance() {
        if(empty(self::$_instance)) {
            $class = __CLASS__;
            self::$_instance = new $class();
        }
        return self::$_instance;
    }

    /**
     * execute $obj->$function and cache the execution result if $reset is true,or get the value from cache by default key
     *
     * @access public
     * @param object $obj Class object
     * @param string $function Name of function
     * @param array $param Parameter array of the function
     * @param mixed $category if is_numeric, timeout seconds, else category of timeout
     * @param bool $reset force cache reset
     * @param array $options other settings
     * @return mixed function result
     */
    public function cached(&$obj, $function, $param=array(), $category=null, $reset=false, $options=array()) {
        $tamsId = TMConfig::get("tams_id");
        $cacheZigzagBase = self::CACHE_ZIGZAG_BASE;
        $cacheZigzagMutiple = self::CACHE_ZIGZAG_MULTIPLE;
        $cacheTimeout = self::cacheTimeout($category);
        return $this->libCached($obj, $function, $param, $tamsId, $category, $cacheZigzagBase, $cacheZigzagMutiple, $cacheTimeout, $reset, $options);
    }
}
?>
