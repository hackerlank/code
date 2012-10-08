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
 * 流程链--添加页面流量监控
 *
 * LIB库内部调用
 * 开关：config/filter.yml - TMTrackFilter
 * 监控代码在 ROOT_PATH . 'templates/tack.php'
 * 如果需要增加第三方监控代码，比如google，只需要修改ROOT_PATH . 'templates/tack.php'往里面添加即可
 *
 * @package sdk.mvc.src.framework.filter
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMTrackFilter.class.php 2009-4-17 by ianzhang
 */
class TMTrackFilter extends TMFilter
{
    protected static $enableAddTrackCode = true;

    public static function getEnableAddTrackCode()
    {
        return self::$enableAddTrackCode;
    }

    public static function setEnableAddTrackCode($enableAddTrackCode)
    {
        self::$enableAddTrackCode = $enableAddTrackCode;
    }

    /**
     * 执行添加监控代码逻辑
     * 监控代码在 ROOT_PATH . 'templates/tack.php'
     * @param TMFilterChain $filterChain
     */
    public function execute($filterChain)
    {
        $filterChain->execute();

        $dispatcher = TMDispatcher::getInstance();
        $needTrack = $dispatcher->getResponse()->getNeedTrackStatus();

        if($needTrack && self::$enableAddTrackCode)
        {
            $view = new TMView();
            $pageTrack = $view->renderFile(array(), ROOT_PATH . "templates/track.php");
            $content = $dispatcher->getResponse()->getContent();
            $replace = "\n".$pageTrack."\n</body>";
            $content = str_replace("</body>",$replace,$content);
            $dispatcher->getResponse()->setContent($content);
        }
    }
}
