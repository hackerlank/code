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
 * 流程链--页面输出逻辑类
 *
 * LIB库内部调用
 * 开关：config/filter.yml - TMRenderingFilter
 *
 * @package sdk.mvc.src.framework.filter
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMRenderingFilter.class.php 2009-4-17 by ianzhang
 */
class TMRenderingFilter extends TMFilter
{
    /**
     * 执行页面输出逻辑
     *
     * @param TMFilterChain $filterChain
     */
    public function execute($filterChain)
    {
        $filterChain->execute();

        $dispatcher = TMDispatcher::getInstance();

        $content = $dispatcher->getResponse()->getContent();
        $length = strlen($content);

        $dispatcher->getResponse()->setHttpHeader("Content-Length", $length);

        $dispatcher->getResponse()->send();
    }
}