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
 * lotteryController
 *
 * @package components.lottery.controllers
 * @author  lynkli <lynkli@tencent.com>
 * @version lotteryController.php 2009-10-29 by lynkli
 */
class lotteryController extends TMComponentController {
	/**
	 * 获取抽奖结果
	 * @param TMWebRequest $request
	 * @return string 抽奖结果字符串，格式为 code=XXX&error=XXX&message=XXX
	 */
	function drawAction() {
		TMBrowserCache::nonCache();
		
		$app = $this->request->getParameter("app", "lottery");
        if (empty($app))
        {
            $app = "lottery";
        }
        try{
            $result = TMLotteryComponent::getInstance($app)->getAward();
            $code = empty($result['item']) ? 0 : $result['item'];
            return json_encode(
                array(
                    "code" => $code,
                    "error" => $result['error'],
                    "message" => $result['message']
                )
            );
        }catch(TMException $te)
        {
            return json_encode(
                array(
                    "code" => 0, 
                    "error" => $te->getCode(),
                    "message" => $te->getMessage()
                )
            );
        }
	}
}