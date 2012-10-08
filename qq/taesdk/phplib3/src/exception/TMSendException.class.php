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
 * TMSendException
 * 发送虚拟物品异常
 *
 * @package sdk.lib3.src.exception
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMSendException.class.php 2010-12-30 by ianzhang
 */
class TMSendException extends TMException {
    /**
     * 构造函数
     *
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code = TMLibExceptionConstant::EXCEPTION_SEND_CODE)
    {
        $this->logException("Qzone_Guajian_Failure: ".$message);
        if(!self::$needShowRealMsg){
            $message = "系统繁忙";
        }
        parent::__construct ( $message, $code );
    }
}
?>