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
 * voteController
 *
 * @package components.vote.controllers
 * @author  lynkli <lynkli@tencent.com>
 * @version voteController.php 2009-10-30 by lynkli
 */
class voteController extends TMComponentController
{
    /**
     * 保存投票结果
     *
     * @param TMWebRequest $request
     * @return string 投票结果数据json
     */
    function saveAction()
    {
        TMBrowserCache::nonCache();

        $app = $this->request->getParameter("app", "vote");
        if (empty($app))
        {
            $app = "vote";
        }

        try
        {
            return TMVoteComponent::getInstance($app)->vote($this->request);
        }
        catch (TMVoteComponentException $ve)
        {
            return $ve->getMessage();
        }
    }
}