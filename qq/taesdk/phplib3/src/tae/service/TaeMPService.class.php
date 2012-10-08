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
 * TAE 营销平台相关接口
 *
 * @package sdk.lib3.src.tae.service
 * @author  happysonxu<happysonxu@tencent.com>
 * @version TaeMPService.class.php 2011-6-16 by happysonxu
 */
class TaeMPService{
    
    /**
     * 发送营销平台物品接口
     * @param string $qq
     * @param int $actId
     * @param int $itemId
     * @param int $count
     * @return array
     */
	public static function sendItem($qq,$actId,$itemId,$count=1)
	{
		$para = array("dstuin"=>$qq,"ruleid"=>$actId,"presentid"=>$itemId,"presenttime"=>$count);
		return TaeCore::taeCall(TaeConstants::CMD_SEND_ITEM,$para);
	}
}
