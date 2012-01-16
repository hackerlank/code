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
 * Tencent mind Xml Exception
 *
 * @package sdk.lib3.src.exception
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMXmlException.class.php 2008-10-24 by ianzhang
 */
class TMXmlException extends TMException
{
    /**
     * 构造函数
     *
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code = TMLibExceptionConstant::EXCEPTION_XML_CODE)
    {
        $this->logException($message);
        parent::__construct ( $message, $code );
    }
}