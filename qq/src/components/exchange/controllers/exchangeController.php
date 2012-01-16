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
 * exchangeController
 *
 * @package components.exchange.controllers
 * @author  lynkli <lynkli@tencent.com>
 * @version exchangeController.php 2010-03-01 by lynkli
 */
class exchangeController extends TMComponentController
{
    /**
     * 保存投票结果
     *
     * @param TMWebRequest $request
     * @return string 投票结果数据json
     */
    function doAction()
    {
        TMBrowserCache::nonCache();

        $app = $this->request->getPostParameter("app", "exchange");
        if (empty($app))
        {
            $app = "exchange";
        }

        try
        {
            return TMExchangeComponent::getInstance($app)->exchangeByScore($this->request);
        }
        catch (TMExchangeComponentException $exge)
        {
            return $exge->getMessage();
        }
    }
}