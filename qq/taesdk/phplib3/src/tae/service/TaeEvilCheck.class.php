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

class TaeEvilCheck{
	
	const PACKAGE_HEAD = 'CCNnNnNnNH8NNNNNa8';
	const PACKAGE_RSP = 'Cstart/ntotol_len/Cversion/Ccmd_type/Nusr_ip/nusr_port/Ncgi_ip/ncgi_port/Nintf_ip/nintf_port/Ncmd_id/Nuin/Nact_id/Nsocket_fd/Nseq/Nsend_time/Nflag/C8reserver/nbody_len/a*body';
	const REPORT_REQ = 'NC';
	const REPORT_RSP = 'Nret_code/Ndetail_code/nmsg_len/a*ret_msg';
	const BLACK_REQ = 'H8NCNNC';
	const BLACK_RSP = 'Nret_code/nmsg_len/a*ret_msg';
	
	const CMD_REPORT = 7001;
	const CMD_BLACK = 7002;
	
	const RSP_MAX_LENGTH = 5120;
	
	const ADSTX = 0x4;
	const  ADETX = 0x5;
	
	const EXT_LENGTH = 4;//length 2 + start 1 + end 1
	
	const NO_RSP = 0;
	const NEED_RSP = 1;
	
	const DEFAULT_BLACK_TIME = 600;
	

	private static $time_out = 1;
	
	/**
	 * @return the $time_out
	 */
	public static function getTimeout() {
		return self::$time_out;
	}

	/**
	 * @param $time_out the $time_out to set
	 */
	public static function setTimeout($time_out) {
		self::$time_out = $time_out;
	}

	/**
	 * 上报
	 * @param int $count
	 * @return bool
	 */
	public static function report($count = 1)
	{
		return self::doCheck($count,self::NO_RSP);
	}
	
	/**
	 * 检测
	 * @param int $count
	 * @return mixed
	 */
	public static function check($count = 1)
	{
		$ret = self::doCheck($count,self::NEED_RSP);
		if(!$ret)
		{
			return false;
		}
		$body = $ret['body'];
		return unpack(self::REPORT_RSP,$body);
	}
	
	/**
	 * 监测，上报接口实现
	 * @param int $count
	 * @param bool $needRsp
	 * @return mixed
	 */
	private static function doCheck($count,$needRsp)
	{
		$seq = 0;
		$head = self::encodeHead(self::CMD_REPORT,$seq);
		$body = pack(self::REPORT_REQ,$count,$needRsp);
		$req = self::encodeAll($head,$body);
		$ret = self::doSend($req,$needRsp);
		
		if(!$ret)
		{
			return $ret;
		}
		
		if(!$needRsp)
		{
			return $ret;
		}
		
		$result = self::decodePackage($ret);
		
		if($result['seq']!=$seq)
		{
			return false;
		}
		
		return $result;
	}
	
	/**
	 * 加入黑名单
	 * @param int $uin 黑名单uin
	 * @param string $ip 黑名单ip
	 * @param int $last 黑名单持续时间，默认10分钟
	 * @param int $start 黑名单开始时间（time_t），默认当前时刻
	 * @param bool $needRsp 是否需要回包
	 * @return mixed
	 */
	public static function addToBlackList($uin=0,$ip='0.0.0.0', $last = self::DEFAULT_BLACK_TIME, $start = null,$needRsp = self::NO_RSP)
	{
		if($uin==0&&$ip=='')
		{
			return false;
		}
		$flag = 0;
		if($uin)
		{
			$flag+=1;
		}
		if($ip!='0.0.0.0')
		{
			$flag+=2;
		}
		if(!$start)
		{
			$start = time();
		}
		$ret = self::doBlackList($uin,$ip,$flag,$start,$last,$needRsp);
		if(!$ret)
		{
			return false;
		}
		$body = $ret['body'];
		return unpack(self::BLACK_RSP,$body);
	}
	
	/**
	 * 从黑名单移除
	 * @param $uin 移除的uin
	 * @param $ip 移除的ip
	 * @param $needRsp 是否需要回包
	 * @return mixed
	 */
	public static function removeFromBlackList($uin=0,$ip='0.0.0.0',$needRsp = self::NO_RSP)
	{
		if($uin==0&&$ip=='')
		{
			return false;
		}
		$flag = 10;
		if($uin)
		{
			$flag+=1;
		}
		if(empty($ip))
		{
			$ip = '0.0.0.0';
		}
		if($ip!='0.0.0.0')
		{
			$flag+=2;
		}
		$ret = self::doBlackList($uin,$ip,$flag,0,0,$needRsp);
		if(!$ret)
		{
			return false;
		}
		$body = $ret['body'];
		return unpack(self::BLACK_RSP,$body);
	}
	
	/**
	 * 黑名单接口实现
	 * @param int $uin
	 * @param string $ip
	 * @param int $flag
	 * @param int $start
	 * @param int $last
	 * @param bool $needRsp
	 * @return mixed
	 */
	private static function doBlackList($uin,$ip,$flag,$start,$last,$needRsp)
	{
		$seq = 0;
		$head = self::encodeHead(self::CMD_BLACK,$seq);
		$body = pack(self::BLACK_REQ,base_convert($uin,10,16),ip2long($ip),$flag,$start,$last,$needRsp);
		$req = self::encodeAll($head,$body);
		$ret = self::doSend($req,$needRsp);
		if(!$ret)
		{
			return $ret;
		}
		if(!$needRsp)
		{
			return $ret;
		}
		$result = self::decodePackage($ret);
		if($result['seq']!=$seq)
		{
			return false;
		}
		return $result;
	}
	
	private function decodePackage($ret)
	{
		$package = $ret;
		$index = 0;
		$result = unpack(self::PACKAGE_RSP,substr($ret,0,strlen($ret)-1));
		$result['end'] = unpack('C',$ret[strlen($ret)-1]);
		return $result;
	}
	
	private function doSend($content,$needRsp = false)
	{
		$sock=@socket_create(AF_INET,SOCK_DGRAM,0);
		if(!$sock){
	     TMDebugUtils::debugLog('fail to create socket');
	     return false;
		}
		$server = TaeCore::getConfig(TaeConstants::SERVER_LIST);
		$host = $server[0]['host'];
		$port = $server[0]['port'];
		if(!@socket_sendto($sock,$content,strlen($content),0,$host,$port)){
		     TMDebugUtils::debugLog('fail to send data');
		     socket_close($sock);
		     return false;
		}
		
		if(!$needRsp)
		{
			socket_close($sock);
			return true;
		}
	
		//stream_set_timeout($sock,self::$time_out);
		$ret = @socket_recvfrom($sock,$rsp,self::RSP_MAX_LENGTH,0,$host,$port);
		socket_close($sock);
		if(!$ret){
		     TMDebugUtils::debugLog('fail to receive data');
		     return false;
		}
		//$info = stream_get_meta_data($sock);
//		if($info['timed_out'])
//		{
//			TMDebugUtils::debugLog('socket time out');
//		     return false;
//		}
		return $rsp;
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
	
}
