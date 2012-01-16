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
 * 事务处理类
 *
 * @package sdk.lib3.src.biz.core
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TransactionService.class.php 2010-1-14 by ianzhang
 */
class TransactionService {
    /**
     * @var TMService
     */
    private static $ts;

    //表示一个事务是否开始
    private static $isStarted = false;

    //读锁map
    private static $readLockArray = array();

    /**
     * 开始事务
     * @param string $dbAlias
     */
    public static function start($dbAlias = "default") {
        if(!self::$isStarted)
        {
            if($ts == null)
            {
                self::$ts = new TMService($dbAlias);
            }
            self::$isStarted = true;
            TMDebugUtils::debugLog("Transaction start");
            self::$ts->startTransaction();
        }
    }

    /**
     * 结束事务，提交事务中所有更新
     * @param string $dbAlias
     */
    public static function commit($dbAlias = "default")
    {
        if(self::$isStarted)
        {
            if($ts == null)
            {
                self::$ts = new TMService($dbAlias);
            }

            TMDebugUtils::debugLog("Transaction commit");
            self::$ts->commit();
            self::setDefault();
        }
    }

    /**
     * 结束事务，回滚事务中所有更新
     * @param string $dbAlias
     */
    public static function rollback($dbAlias = "default")
    {
        if(self::$isStarted)
        {
            if($ts == null)
            {
                self::$ts = new TMService($dbAlias);
            }

            TMDebugUtils::debugLog("Transaction rollback");
            self::$ts->rollback();
            self::setDefault();
        }
    }

    /**
     * 将事务所有成员属性设置回初始状态
     */
    private static function setDefault()
    {
        self::$isStarted = false;
        self::$readLockArray = array();
    }

    /**
     * 查看该事务是否针对记录加上了读锁
     *
     * @param string $key         锁映射的键
     */
    public static function getLockStatus($key)
    {
        if(!isset(self::$readLockArray[$key]))
        {
            return false;
        }
        else
        {
            return self::$readLockArray[$key];
        }
    }

    /**
     * 设置该事务的读锁状态
     *
     * @param string $key        锁映射的键
     * @param boolean $lockStatus   读锁的状态
     */
    public static function setLockStatus($key, $lockStatus)
    {
        self::$readLockArray[$key] = $lockStatus;
    }

    /**
     * 获得当前事务的状态
     *
     * @return Boolean
     */
    public function getTransactionStatus()
    {
        return self::$isStarted;
    }
}