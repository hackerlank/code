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
 *
 */
class TaeMonitorService{

    const HOST = '127.0.0.1';
    const PORT = 25000;
    const PACKAGE_HEAD = 'CCNnNnNnNH8NNNNNa8';
    const MONITOR_REQ = 'NN';
    const EXT_LENGTH = 4;//length 2 + start 1 + end 1
    const ADSTX = 0x4;
    const ADETX = 0x5;

    protected static $enableReport = true;

    public static function setEnableReport($enableReport)
    {
        self::$enableReport = $enableReport;
    }

    public static function getEnableReport()
    {
        return self::$enableReport;
    }

    public static function attrReport($id,$value=1)
    {
        if(self::$enableReport){
            $seq = 0;
            $head = self::encodeHead(TaeConstants::CMD_MONITOR,$seq);
            $body = pack(self::MONITOR_REQ,$id,$value);
            $req = self::encodeAll($head,$body);
            $ret = self::doSend($req);
            return $ret;
        }else{
            return true;
        }
    }

    private static function encodeAll($head,$body)
    {
        $length = strlen($body);
        $body = pack('n',$length).$body;
        $ext = pack('C',0);
        $result = $head.$body.$ext;
        $length = strlen($result) + self::EXT_LENGTH;
        $result = pack('C',self::ADSTX).pack('n',$length).$result.pack('C',self::ADETX);
        return $result;
    }

    private static function encodeHead($cmd,&$seq,$uin = null)
    {
        $version = 1;
        $cmd_type = 1;
        $usr_ip = ip2long(TaeCore::getConfig(TaeConstants::USER_IP));
        $usr_port = 0;
        $cgi_ip = ip2long(isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:'0.0.0.0');
        $cgi_port = 0;
        $intf_ip = 0;
        $intf_port = 0;
        $uin = TaeCore::getConfig(TaeConstants::UIN);
        $act_id = TaeCore::getConfig(TaeConstants::ACT_ID);
        $socket_fd = 0;
        $seq = rand(1,PHP_INT_MAX);
        $send_time = time();
        $flag = 0;
        $reserve = '';

        $result = pack(self::PACKAGE_HEAD,$version,$cmd_type,$usr_ip,$usr_port,$cgi_ip,$cgi_port,$intf_ip,$intf_port,$cmd,str_pad(base_convert($uin,10,16),8,'0',STR_PAD_LEFT),$act_id,$socket_fd,$seq,$send_time,$flag,$reserve);

        return $result;
    }

    private function doSend($content)
    {
        $sock=@socket_create(AF_INET,SOCK_DGRAM,0);
        if(!$sock){
            TMDebugUtils::debugLog('fail to create socket');
            return false;
        }
        $host = self::HOST;
        $port = self::PORT;
        if(!@socket_sendto($sock,$content,strlen($content),0,$host,$port)){
            TMDebugUtils::debugLog('fail to send monitor data');
            socket_close($sock);
            return false;
        }

        return true;
    }
}

