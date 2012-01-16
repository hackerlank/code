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
 * TAE B2接口服务
 *
 * @package sdk.lib3.src.tae.service
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TaeB2Service.class.php 2011-6-16 by ianzhang
 */
class TaeB2Service{
    /**
     * 获取品牌空间粉丝列表
     * 
     * 这个接口，一次最多只能拉取1000个粉丝，如果需要全部拉取的话，注意下文档里面的这块：
     * 注：客户端拉取粉丝列表时，如需全部拉取粉丝，则遵循以下步骤
     * 1）  请求协议中的起始ID和结束ID第一次均填0和1000。
     * 2）  判断返回协议中retcode是否为0，如不为0，则结束流程，否则，继续往下执行。
     * 3）  判断拉取的粉丝列表数是否小于1000，则说明已经拉完，结束流程。如果等于1000，则进行下一次拉取。起始ID和结束ID填上一次的后续累加值（如前一次是0和1000，那么下一次就是1001和2000，再下一次就是2001和3000，依次类推。），直到拉完为止。
     * 4）  返回到第2步执行。
     * 
     * @param $qq
     * @return array
     */
	public static function getBrandFansList($qq, $begin, $end)
	{
	    $ipArray = TMConfig::get("tae", "fastcgi");
	    
	    $fastcgi = $ipArray[mt_rand(0, count($ipArray)-1)];
	    
	    $ip = $fastcgi["host"];
	    
	    $url = "http://$ip/qzone/follow_fanslist";
	    
	    $vhost = $fastcgi["vhost"];
	    
	    $params = array(
           "uin" => $qq,
           "begin" => $begin,
           "end" => $end,
           "usr_ip" => TMUtil::getClientIp()
        );

        return TaeCore::fastCgiCall($url, $params, $vhost);
	}
}