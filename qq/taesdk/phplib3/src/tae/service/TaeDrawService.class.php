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
 * 抽奖服务
 * 
 * @package sdk.lib3.src.tae.service
 * @author  happysonxu<happysonxu@tencent.com>
 * @version TaeDrawService.class.php 2011-8-9 by happysonxu
 *
 */
class TaeDrawService {
	
	/**
	 * 抽奖方法
	 * @param int $lotto_id 抽奖ID（活动内）
	 * @param int $lotto_stage 期号（默认为0，不判断）
	 * @param array $shield_awards 屏蔽奖品ID信息（数组内容为需要屏蔽的每个奖品ID），包长限制不超过1024个数值
	 */
	public static function draw($lotto_id, $lotto_stage=0, $shield_awards = array())
	{
		$shield_str = implode(';',$shield_awards);
		$para = array('lotto_id'=>$lotto_id,'lotto_stage'=>$lotto_stage,'shield_awards'=>$shield_str);
		 
		$result = TaeCore::taeCall(TaeConstants::CMD_DRAW_DO,$para);
		
		return $result;
	}
	
	/**
	 * 查询抽奖
	 * @param int $lotto_id 抽奖ID（活动内）
	 * @param int $lotto_stage 期号（默认为0，不判断）
	 */
	public static function awardQuery($lotto_id, $lotto_stage=0)
	{
		$para = array('lotto_id'=>$lotto_id,'lotto_stage'=>$lotto_stage);
		$result = TaeCore::taeCall(TaeConstants::CMD_DRAW_QUERY,$para);
		
		foreach($result["awards_list"] as &$award){
			$award["win_time"] = date("Y-m-d H:i:s", $award["win_time"]);
			$award["win_ip"] = long2ip($award["win_ip"]);
		}
		
		return $result;
	}

}

?>