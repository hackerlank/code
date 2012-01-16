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

static $exceptionClass;
if(@class_exists("TMException"))
{
	$exceptionClass = "TMException";
}else{
	$exceptionClass = "Exception";
}

eval("class TaeBaseException extends $exceptionClass {}");

/**
 * TAE异常类
 *
 * @package sdk.lib3.src.tae.core
 * @author  happysonxu<happysonxu@tencent.com>
 * @version TaeException.class.php 2011-6-16 by happysonxu
 */
class TaeException extends TaeBaseException {
	
	private $request;
	private $response;
	private $ret_code;
	
	/**
	 * 构造函数
	 * @param strng $message
	 * @param int $code
	 */
	public function __construct($message, $code = TaeConstants::EXCEPTION_TAE)
    {
        parent::__construct ( $message, $code );
    }
    
    /**
     * 获得request
     * @return mixed
     */
    public function getRequest()
    {
    	return $this->request;
    }
    
    /**
     * 获得返回结果
     * @return mixed
     */
    public function getResponse()
    {
    	return $this->response;
    }
    
    /**
     * 设置请求
     * @param mixed $request
     */
    public function setRequest($request)
    {
    	$this->request = $request;
    }
    
    /**
     * 设置返回值
     * @param mixed $response
     */
	public function setResponse($response)
    {
    	$this->response = $response;
    }
    
    /**
     * 设置返回码
     * @param int $ret_code
     */
    public function setRetCode($ret_code)
    {
    	$this->ret_code = $ret_code;
    }
    
    /**
     * 获取返回码
     * @return int
     */
    public function getRetCode()
    {
    	return $this->ret_code;
    }
}
?>