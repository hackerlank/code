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
 * TMAPCMgr
 * APC管理类
 *
 * @package sdk.mvc.src.framework.cache
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMAPCMgr.class.php 2010-12-27 by ianzhang
 */
class TMAPCMgr extends TMLibAPCMgr {
    const CACHE_ZIGZAG_BASE = 30;
    const CACHE_ZIGZAG_MULTIPLE = 5;

    protected static $_cacheCategories = array(
        'default'    => 30,
        'config'    => 3600
        );

    private static $_instance = null;

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
     * @param int $expire  超时
     * @param string $nameSpace 命名空间
     * @return void
     */
    protected function __construct( $expire = 0, $nameSpace = null) {
        if(empty($nameSpace))
        {
            $nameSpace = TMConfig::get("namespace");
        }
        parent::__construct($nameSpace, $expire);
    }

    /**
     * 得到一个实例
     * @param int $expire 超时
     * @param string $nameSpace 命名空间
     * @return void
     */
    public static function getInstance( $expire = 0, $nameSpace = null ) {
        if(empty($nameSpace))
        {
            $nameSpace = TMConfig::get("namespace");
        }
        if(empty(self::$_instance)) {
            $class = __CLASS__;
            self::$_instance = new $class($expire,$nameSpace);
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
