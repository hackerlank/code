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
 * TMAbstractDebugger
 * 抽象调试器
 *
 * @package sdk.lib3.src.base.core
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMAbstractDebugger.class.php 2010-12-30 by ianzhang
 */
class TMAbstractDebugger{
    protected $name;

    protected static $needAddToDebugger = false;

    public static function setNeedAddToDebugger($needAddToDebugger)
    {
        self::$needAddToDebugger = $needAddToDebugger;
    }

    public static function getNeedAddToDebugger()
    {
        return self::$needAddToDebugger;
    }

    /**
     * 获得Debugger的名字
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 将调试信息加入Debugger中
     */
    public function add()
    {

    }

}
?>