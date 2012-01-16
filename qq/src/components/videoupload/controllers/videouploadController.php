<?php

/*
 * ---------------------------------------------------------------------------
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
 * ---------------------------------------------------------------------------
 */

/**
 * videouploadController
 *
 * @package components.fileupload.controllers
 * @author  gastonwu <gastonwu@tencent.com>
 * @version videouploadController 2011-08-09 by gastonwu
 */
class videouploadController extends TMComponentController {

    /**
     * 获取图片上传ticket
     */
    public function ticketAction() {
        $input = array(
            "title" => $_REQUEST["title"],
            "tags" => $_REQUEST["tags"],
            "cat" => $_REQUEST["cat"],
            "desc" => $_REQUEST["desc"],
        );

        return TMVideoUploadComponent::getInstance()->getTicket($input);
    }

}