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
 * OmsLogService
 * 记录oms报警信息
 *
 * @package sdk.mvc.src.framework.service
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version OmsLogService.class.php 2010-12-27 by ianzhang    
 */
class OmsLogService extends TMLibOmsLogService{
    /**
     * 得到实例化句柄
     * @param string $logBaseDir  日志记录地址
     * @return OmsLogService
     */
    public static function getInstance($logBaseDir="")
    {
        $tamsId = TMConfig::get("tams_id");
        if(empty($logBaseDir))
        {
            $logBaseDir="/data/log/oms";
        }
        if(isset(self::$instance) === false){
            self::$instance = new OmsLogService($tamsId, $logBaseDir);
        }
        return self::$instance;
    }
}
?>