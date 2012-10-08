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
 * The slot class
 *
 * @package sdk.mvc.src.framework.core
 * @author  lynkli <lynkli@tencent.com>
 * @version TMSlot.class.php 2009-11-04 by lynkli
 */
class TMSlot
{
    /**
     * 调用一个slot
     * 先到DefaultSlot（如果有自定义则是自定义的类）中查找$action是否存在
     * 如果存在则调用$action函数处理
     * 否则直接render相应的slot模板
     *
     * @param string $action slot动作函数名
     * @param array $params slot所需参数
     * @param string $slot slot名，用于组装slot类名，例如 DefaultSlot，slot类可以理解为slot的controller
     */
    public static function call($action, $params=array(), $slot='default')
    {
        $class = ucfirst($slot) . "Slot";

        $obj = new $class();
        if (method_exists($obj, $action))
        {
            return $obj->$action($params);
        }
        else
        {
            $view = new TMView();
            $tpl = "slot/" . $slot . $action . ".php";
            return $view->renderFile($params, $tpl);
        }
    }

    /**
     * 引用一个slot，并输出slot内容
     *
     * @param string $action slot动作函数名
     * @param array $params slot所需参数
     * @param string $slot slot名
     */
    public static function includeSlot($action, $params=array(), $slot='default')
    {
        echo self::call($action, $params, $slot);
    }
}
