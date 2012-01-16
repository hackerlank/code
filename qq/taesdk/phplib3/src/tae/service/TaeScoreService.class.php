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
 * TAE积分服务
 *
 * @package sdk.lib3.src.tae.service
 * @author  happysonxu <happysonxu@tencent.com>
 * @version TaeScoreService.class.php 2011-6-16 by happysonxu
 */
class TaeScoreService {
	const STRICT_NONE = 0;
	const STRICT_MAX = 1;
    /**
     * 给uin更新积分（加、减） & 同步分类计总
     * @param string $toUin 给此uin加分
     * @param int $score
     * @param int $scoreType 需求计总的type,例如:1,2对应原score表的score1,score2字段
     * @param int $strict_type 限制类型 0:不限制 1:最大值限制 2:最后值限制
     * @param int $strict_val 根据strict_type做不同解释：当strict_type为0时，此值需填0;为1时，此值为限制最大值;为2时,此值为最后值
     * @param int $sourceId  1:登录,2:注册,3:评论,4:投票,5:邀请好友,6:发微薄,7:上传,8:推荐,9:抽奖,10:Flash游戏,11其他
     * @param string $fromUin  相关uin
     * @param string $remark 注释
     * @return string 
     */
    public static function updateScore($toUin, $score,  $scoreType, $strictType = TaeScoreService::STRICT_NONE, $strictVal=0, $sourceId= 11 ,$fromUin = 0,$remark='') {
        $para = array(
            'key_value' => $toUin,
            'src_id' => $fromUin,
            'strict_type'=>$strictType,
            'strict_val' => $strictVal,
            'score_val' => $score,
            'score_type' => $scoreType,
            'source_id' =>$sourceId ,
            'remark' => $remark,
        );
        
        return TaeCore::taeCall(TaeConstants::CMD_SCORE_UPDATE, $para);
    }

    /**
     * 查询uin总积分 & 分类总分
     * @param string $uin 要查询的uin
     * @param string $typelist 要查询的类型
     * @return string 
     */
    public static function queryScore($uin,$typelist){
    	$typeStr = '';
    	foreach ($typelist as $type){
    		$typeStr.="$type;";
    	}
        $typeStr = substr($typeStr,0,strlen($typeStr)-1);
        $para = array(
            'key_value' => $uin,
            'typelist' => $typeStr,
        );
        
        return TaeCore::taeCall(TaeConstants::CMD_SCORE_SEARCH, $para);
    }
}
