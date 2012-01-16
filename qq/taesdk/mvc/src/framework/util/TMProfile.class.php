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
 * TMProfile
 * 进行程序时间计算辅助类
 *
 * @package sdk.mvc.src.framework.util
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMProfile extends TMLibProfile.class.php 2010-12-30 by ianzhang    
 */
class TMProfile extends TMLibProfile {
    /**
     * 输出时间
     *
     * @param string $key
     * @return string
     */
    public static function out($key) {
        $log = new TMLog();
        return parent::out($key, $log);
    }
}
?>