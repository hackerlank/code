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
 * The lock service
 *
 * @package sdk.lib3.src.biz.core
 * @author  samonma <samonma@tencent.com>
 * @version LockService.class.php 2009-09-24 by samonma
 */
class TMLibLockService
{
    /**
     * 在缓存管理器中设置指定的值，来做标记（上锁）
     *
     * @param string $key 标记名
     * @param integer $expire 过期时间
     * @param TMCacheInterface $cacheInterface
     * @return 缓存管理器所返回的提示信息
     */
    public static function lock($key, $expire, TMCacheInterface $cacheInterface)
    {
        return $cacheInterface->add($key, 'lock', $expire);
    }

    /**
     * 清除之前在缓存管理器中设定的指定值(解锁)
     *
     * @param string $key 标记名
     * @param TMCacheInterface $cacheInterface
     */
    public static function unlock($key, TMCacheInterface $cacheInterface)
    {
        $cacheInterface->clear($key);
    }
}