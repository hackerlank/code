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
 * Manage the browser cache
 *
 * @package sdk.lib3.src.base.ext
 * @author  Lynk Li <lynkli@tencent.com>
 * @version 2010-09-10
 */
class TMBrowserCache
{
    /**
     * set browser cache by seconds
     *
     * @access public
     * @param int $seconds the expire time
     * @return void
     */
    static public function cache($seconds)
    {
        $response = TMWebResponse::getInstance();
        $response->setHttpHeader("Last-Modified", gmdate ("D, d M Y H:i:s", time()) . " GMT");
        $response->setHttpHeader("Expires", gmdate ("D, d M Y H:i:s", time() + $seconds) . " GMT");
        $response->setHttpHeader("Cache-Control", "public");
    }

    /**
     * make page not be cached by browser
     *
     * @access public
     * @return void
     */
    static public function nonCache()
    {
        $response = TMWebResponse::getInstance();
        $response->setHttpHeader("Last-Modified", gmdate ("D, d M Y H:i:s", time()) . " GMT");
        $response->setHttpHeader("Expires", gmdate ("D, d M Y H:i:s", time() - 3600) . " GMT");
        $response->setHttpHeader("Cache-Control", "private");
    }
}
