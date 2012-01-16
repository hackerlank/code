<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2009 BY  TENCENT  CORPORATION.  ALL RIGHTS
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
 * Available config handle
 *
 * @package sdk.mvc.src.framework.config
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMAvailableConfigHandle.class.php 2009-4-20 by ianzhang
 */
class TMAvailableConfigHandle
{
    /**
     * execute the available config handle, return the available config content
     *
     * @access public
     * @param string $configFile config file path
     * @return string $content
     */
    public function execute($configFile)
    {
        $array = TMYaml::load($configFile);
        $apcMgr = new TMAPCMgr(0, $array["name"]);

        $availableList = $array["availableList"];
        if(!is_array($availableList))
        {
            $availableList = "EMPTYLIST";
        }
        $apcMgr->set("availableList",$availableList);

        $content = "<?php\n";
        $content .= "\$availableConfigName = '".$array["name"]."';";
        $content .= "\n?>";

        return $content;
    }
}