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
 * Builder exception
 *
 * @package sdk.mvc.src.framework.exception
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMBuilderException.class.php 2010-7-26 by ianzhang    
 */
class TMBuilderException extends TMException{
    /**
     * 构造函数
     * @param string $message
     * @param int $code
     * @return void
     */
    public function __construct($message, $code = TMConfigConstant::EXCEPTION_BUILDER)
    {
        parent::__construct ( $message, $code );
    }
}

?>