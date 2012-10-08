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
 * 用于记录程序或者某个代码段的执行时间，并将执行时间写入到日志文件中
 *
 * Usage:
 * 在程序开始执行的地方
 * <code>
 *   $key = "some programm";
 *   TMProfile::in($key);
 * </code>
 * 在程序结束执行的地方
 * <code>
 *   $key = "some programm"; //需要和程序开始执行时设置的$key一样
 *   TMProfile::out($key);
 * </code>
 *
 * @package sdk.lib3.src.biz.core
 * @author  lynkli <lynkli@tencent.com>
 * @version TMProfile.class.php 2009-11-06 by lynkli
 */
class TMLibProfile
{
    private static $profiles;

    /**
     * 开始计算程序执行时间
     *
     * @param string $key 程序块ID
     * @return boolean
     */
    public static function in($key)
    {
        self::$profiles[$key] = array("in"=>microtime(true));
        return true;
    }

    /**
     * 结束计算程序执行时间
     * 将程序块执行时间记录到日志
     *
     * @param string $key 程序块ID
     * @param TMLogInterface $log
     * @return boolean
     */
    public static function out($key, TMLogInterface $log)
    {
        if (!isset(self::$profiles[$key]))
        {
            return true;
        }
        self::$profiles[$key]['out'] = microtime(true);
        $used = self::$profiles[$key]['out'] - self::$profiles[$key]['in'];
        $log->ll("$key used: " . $used);
        return true;
    }
}