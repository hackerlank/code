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
 * TAE注册服务
 *
 * @package sdk.lib3.src.tae.service
 * @author  gastonwu <gastonwu@tencent.com>
 * @version TaeRegisterService.class.php 2011-6-16 by gastonwu
 */
class TaeRegisterService {

    /**
     * 登陆即注册
     * @param int $query_flag default0,具体释义:todo mango
     * @return string
     */
    public static function loginRecord($query_flag=0) {
        $para = array(
            'query_flag'=>$query_flag,
        );
        
        return TaeCore::taeCall(TaeConstants::CMD_REG_LOGINRECORD, $para);
    }

    /**
     * 注册
     * @param array $option 注册字段选项
     * @return string
     */
    public static function register($option=array()) {
        $para = $option;
        
        return TaeCore::taeCall(TaeConstants::CMD_REG_REGISTER, $para);
    }
    
    /**
     * 建议要可以查询指定的uin
     */
    public static function getRegisterInfo(){
        $para = array();
        
        return TaeCore::taeCall(TaeConstants::CMD_REG_INFO, $para);
        
    }
    
    /**
     * 更新信息开放标志
     * @param int $open_flag 信息开放标志，0-不开放，1-开放
     */
    public static function updateOpenFlag($open_flag){
        $para = array(
            'open_flag'=>$open_flag,
        );
        
        return TaeCore::taeCall(TaeConstants::CMD_REG_UPDATEOPENFLAG, $para);
        
    }
}