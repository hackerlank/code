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
 * 组件controller基类
 *
 * @package sdk.mvc.src.framework.core
 * @author  lynkli <lynkli@tencent.com>
 * @version TMComponentController.class.php 2009-11-25 by lynkli
 */
class TMComponentController extends TMController {
    protected $componentDir = '';

    /**
     * 构造函数
     *
     * @access public
     * @param  string $controllerName   the controller name
     * @param  string $actionName       the action name
     */
    public function __construct($controllerName, $actionName) {
        parent::__construct($controllerName, $actionName);
        $component = TMDispatcher::getInstance()->getComponent();
        $this->componentDir = TMDispatcher::getComponentsDir($component);
    }
}