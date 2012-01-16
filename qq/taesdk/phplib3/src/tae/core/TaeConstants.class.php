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
 * TAE内部常量表
 *
 * @package sdk.lib3.src.tae.core
 * @author  happysonxu<happysonxu@tencent.com>
 * @version TaeConstants.class.php 2011-6-16 by happysonxu
 */
class TaeConstants{
    //前缀常量
    const HEADER_PREFIX= 'header.';
    const BODY_PREFIX = 'body.';
    //配置项常量
    const UIN = 'uin';
    const USER_IP = 'usr_ip';
    const ACT_ID = 'act_id';
    //const CONF_FILE = 'conf_file';
    const VERSION = 'version';
    const SERVER_IP = 'server_ip';
    const SERVER_PORT = 'server_port';
    const SERVER_LIST = 'server_list';
    const CONNECT_TIMEOUT = 'connect_timeout';
    const TIMEOUT ='timeout';
    
    const WEIBO_HTTP_SERVER = "weiboServer";
    const WEIBO_TAE_SERVER = "taeServer";
    
    //命令号
    const CMD_GET_FRIEND_LIST = 1001;//获取好友列表
    const CMD_GET_NICK = 1004;//批量获取昵称
    const CMD_GET_FRIEND_GROUP = 1000;//获取好友分组
    const CMD_SEND_ITEM = 1100;//MP系统发奖
    const CMD_QZONE_BLOG = 1201;//QZone日志
    const CMD_QZONE_PENDANT = 1202;//QZone挂件
    const CMD_QZONE_VOTE = 1204;//QZone投票
    const CMD_SCORE_UPDATE = 2001; //积分更新
    const CMD_SCORE_SEARCH = 2000; //积分查询
    const CMD_REG_LOGINRECORD = 2101; //登陆记录
    const CMD_REG_REGISTER = 2102; //用户资料信息登记
    const CMD_REG_INFO = 2103; //用户资料信息查询
    const CMD_REG_UPDATEOPENFLAG = 2104; //设置信息开放标志
    const CMD_COUNTER_QUERY_DAY = 3001;//计数服务查询计数,按天计数
    const CMD_COUNTER_ADD_DAY = 3002;//计数服务修改计数,按天计数
    const CMD_COUNTER_QUERY_ALL = 3003;//计数服务查询计数，查询总数
    const CMD_COUNTER_ADD_ALL = 3004;//计数服务修改计数，查询总数
    const CMD_COUNTER_QUERY_DAY_EXT = 3005;//计数服务查询计数,按天计数，增加扩展key
    const CMD_COUNTER_ADD_DAY_EXT = 3006;//计数服务修改计数,按天计数，增加扩展key
    const CMD_COUNTER_QUERY_ALL_EXT = 3007;//计数服务查询计数，查询总数，增加扩展key
    const CMD_COUNTER_ADD_ALL_EXT = 3008;//计数服务修改计数，查询总数，增加扩展key
    const CMD_DRAW_DO = 2201;//抽奖服务抽奖操作
    const CMD_DRAW_QUERY = 2202;//抽奖服务查询抽奖
    const CMD_MONITOR = 5000;//公司网管上报
    //错误码
    const ERR_FILE_NOT_FOUND = -1;
    const ERR_NOT_INITED = -2;
    const ERR_DECODE_FAIL = -3;
    const ERR_NO_AVAILABLE_SERVER = -4;
    //exception code
    const EXCEPTION_TAE = 22;
    
    /**
     * 获取指令服务器
     * @param $cmd
     * @return string
     */
    public static function getCommandServer($cmd)
    {
        if($cmd>=1000&&$cmd<=1099)
        {
            return "oidbproxy";
        }else if($cmd>=1100&&$cmd<=1199)
        {
            return "mpproxy";
        }else if($cmd>=1200&&$cmd<=1299)
        {
            return "b2proxy";
        }else if($cmd>=2000&&$cmd<=2099)
        {
            return "taescoresvr";
        }else if($cmd>=2100&&$cmd<=2199)
        {
            return "taeregsvr";
        }else if($cmd>=3000&&$cmd<=3099)
        {
            return "taecountersvr";
        }else{
            return "";
        }
    }
}
?>