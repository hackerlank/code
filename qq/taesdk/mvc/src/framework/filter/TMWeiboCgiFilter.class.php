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
 * TMWeiboCgiFilter
 * 处理全站TAE使用包的提前设置
 *
 * @package sdk.mvc.src.framework.filter
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMFastWeiboCgiFilter.class.php 2010-12-30 by ianzhang
 */
class TMWeiboCgiFilter extends TMFilter {
    
    /**
     * Filter的执行方法
     * @param TMFilterChain $filterChain
     */
    public function execute($filterChain) {
        
        $dispatcher = TMDispatcher::getInstance();
        $request = $dispatcher->getRequest();
        
        $uri = $request->getRelativeUrlRoot();

        if(preg_match("/^\/weibo\-cgi\/(.+)/", $uri, $matches))
        {
            //进行weibo call转发
            $weiboUri = $matches[1];
            
            $method = $request->getMethod();
            
            $isPost = $method == TMRequest::POST ? true : false;
            
            if($isPost){
                $param = $request->getPostParameters();
            }else{
                $param = $request->getGetParameters();
            }

            $result = TaeCore::weiboCall($weiboUri, $param , $isPost);
            
            $dispatcher->getResponse()->setContent($result);
        }
        else{
            $filterChain->execute();
        }
    }
}