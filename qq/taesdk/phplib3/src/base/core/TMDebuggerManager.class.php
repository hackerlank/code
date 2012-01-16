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
 * 调试器管理器
 *
 * @package sdk.lib3.src.base.core
 * @author  ianzhang <ianzhang@tencent.com>
 * @version 2011-6-16
 */
class TMDebuggerManager{

    protected static $debuggers = array();

    /**
     * 将调试数据加入指定的调试器中
     * @param string $debuggerName
     * @param array $params
     */
    public static function add($debuggerName, $params = array())
    {
        $debugger = self::getDebugger($debuggerName);

        $reflectionMethod = new ReflectionMethod($debugger, "getNeedAddToDebugger");
        $needAddStatus = $reflectionMethod->invoke(null);

        if(!empty($debugger) && $needAddStatus){
            call_user_func_array(array($debugger, "add"), $params);
        }
    }

    /**
     * 增加一个调试器都管理容器中
     * @param TMAbstractDebugger $debugger
     */
    public static function addDebugger($debugger)
    {
        $debuggerName = $debugger->getName();
        self::$debuggers[$debuggerName] = $debugger;
    }

    /**
     * 获得一个调试器
     * @param string $debuggerName
     * @return TMAbstractDebugger
     */
    public static function getDebugger($debuggerName)
    {
        if(!isset(self::$debuggers[$debuggerName]))
        {
            $className = "TM".ucfirst($debuggerName)."Debugger";
            $object = new $className();
            self::addDebugger($object);
        }
        return self::$debuggers[$debuggerName];
    }
}
?>