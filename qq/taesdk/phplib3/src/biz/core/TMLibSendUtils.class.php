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
  * The util class for sending virtual goods
  *
  * @package sdk.lib3.src.biz.core
  * @author  ianzhang <ianzhang@tencent.com>
  * @version TMSendUtils.class.php 2009-9-27 by ianzhang
  */
 class TMLibSendUtils
 {
    /**
     * 发送自定义qqshow，例如用户上传图片，保存为qqshow
     * @param string $ip    qqshow服务器ip
     * @param string $port  qqshow服务器端口
     * @param string $qq    用户qq
     * @param string $imageFilePath 自定义qqshow图片url，注意图片大小需符合qqshow正常尺寸
     * @return boolean     true:success false:fail
     */
     public static function sendCustomizedQQshow($ip, $port, $qq, $imageFilePath, $nameSpace) {
        try{
            $imageContent = file_get_contents ( $imageFilePath );
        } catch (TMException $te){
            $log->la("open image file error!");
        }
        $len = strlen ( $imageContent );
        $ver = iconv ( "utf-8", "gb2312", 0x01 );
        $cmd = iconv ( "utf-8", "gb2312", 0x12 );
        $pcb = iconv ( "utf-8", "gb2312", "" );
        $reqLen = iconv ( "utf-8", "gb2312", 314 );
        $cmd = iconv ( "utf-8", "gb2312", 0x12 );
        $gid = iconv ( "utf-8", "gb2312", 9 );
        $uin = iconv ( "utf-8", "gb2312", $qq );
        $price = iconv ( "utf-8", "gb2312", 30 );
        $days = iconv ( "utf-8", "gb2312", 30 );
        $bid = iconv ( "utf-8", "gb2312", 0 );
        $reserve1 = iconv ( "utf-8", "gb2312", 0 );
        $bit = iconv ( "utf-8", "gb2312", "" );
        $itype = iconv ( "utf-8", "gb2312", "" );
        $imageName = iconv ( "utf-8", "gb2312", "" ) . "\0";
        $width = iconv ( "utf-8", "gb2312", 0 );
        $height = iconv ( "utf-8", "gb2312", 0 );
        $x = iconv ( "utf-8", "gb2312", 0 );
        $y = iconv ( "utf-8", "gb2312", 0 );
        $len = iconv ( "utf-8", "gb2312", $len );
        $image = $imageContent;

        $log->la ( "imagelen" . $len );
        $format = "cca40NNNNNNN4cca256NNNNNa" . $len;
        $data = pack ( $format, $ver, $cmd, $pcb, $reqLen, $gid, $uin, $price, $days, $bid, $reserve1, $reserve1, $reserve1, $reserve1, $bit, $itype, $imageName, $width, $height, $x, $y, $len, $image );
        try {
            $socket = new TMSocket ( );
            $socket->connect ( $ip, $port );
            $result = $socket->sendData ( $data );
        } catch ( TMRemoteException $te ) {
            $log->la ( "socket fail" );
            throw $te;
        }
        $socket->close ();
        if ($result == 0) {
            return true;

        } else {
            return false;
        }
    }

    /**
     * 发送带图片的Qzone Feeds
     * 特别注意如果Qzone开放给所有用户的话，图片如果是放在广平服务器可能会压力较大支持不住
     * 支持弱验证的Qzone服务器是他们的正式服务器，需要他们发布以后才能测试。
     * 同一个actid无论是测试环境还是正式环境，只要发过一次就不能再发送了。
     *
     * @param int $actid the qzone send action id
     * @param string $qq the qq number
     * @param array $images 图片数组，包含若干组图片，每组图片则有两张一张为原始大小的图片，另外一张为缩略图；例如 array("0"=>array("o"=>"http://xxx/xxx.jpg","t"=>"http://xxx/xxx1.jpg"));
     * @return boolean true：发送成功，false：发送失败
     */
    public static function sendImageFeeds($actid, $qq, $images=array())
    {
        //支持弱验证的Qzone服务器IP
        $ip = '172.23.129.119';
        $curl =  new TMCurl("http://".$ip."/user/freereg.php?act_id=".$actid."&unicode");

        $fields = array();
        foreach ($images as $k=>$image)
        {
            $fields["albumart[$k][o]"] = $image['o'];
            $fields["albumart[$k][t]"] = $image['t'];
        }

        //以弱验证的方式传入用户登录信息
        $fields['uin'] = $qq;
        $fields['md5'] = md5('mudi5n'.$qq.'#$ui982~)=c+^%3*');
        $fields["qq"] = $qq;

        //过滤、拼接传入参数
        $fields_string = TMUtil::handleParameter($fields);
        //过滤、拼接cookie
        $cookie_string = TMUtil::handleParameter($_COOKIE, ";");

        //设置curl options
        $option = array(
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_COOKIE => $cookie_string,
            CURLOPT_POST => count($fields),
            CURLOPT_POSTFIELDS => $fields_string,
            CURLOPT_HTTPHEADER => array('Host: act.qzone.qq.com')
            );
        $curl->setOptions($option);

        try
        {
            $result = $curl->execute();
        }
        catch (TMException $te)
        {
            TMDebugUtils::debugLog($te->getMessage());
            return false;
        }

        $result_array = json_decode($result, true);
        if (array_key_exists('err', $result_array))
        {
            try{
                throw new TMSendException("Send Guajian Array, err: ".$result_array['err'].", msg: ".$result_array['msg']);
            }catch(TMSendException $se)
            {
                return false;
            }
        }

        return true;
    }
 }