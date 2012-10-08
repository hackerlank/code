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
 * defaultController
 *
 * @package controllers
 * @author  qrangechen <qrangechen@tencent.com> 
 * @version defaultController.class.php 2011-11-14 by qrangechen    
 */
class defaultController extends ProjectController{

    public function defaultAction()
    {    
        $request = $this->request;
        //TODO 添加你自己的处理方法
        
        TMDebugUtils::debugLog("xx");
        return $this->render();
        
        /**********************************
                    如果希望首页数据库访问崩溃，首页其他部分还能正常访问而不跳转到error页面
                     ，则可以捕获可能出错的地方，来自定义出错处理
        try{
            $request = $this->request;
            
            //关于db调用
        }catch(TMMysqlException $me)
        {
            //处理数据库异常
        }catch(TMMemcacheException $mce)
        {
            //处理memcache异常
        }
        ************************************/     
    }
    /**
     * 事务处理
     */
    public function trantestAction()
    {
        TMBrowserCache::nonCache();
        TMUtil::getClientIp();
        TaeEvilCheck::report();
    	try {
    		TransactionService::start();
    		$ts = new TMService();
    		TransactionService::commit();
    	}catch (TMException $e) {
    		TransactionService::rollback();
    	}
    }
    /**
     * form test
     */
    public function testformAction()
    {
        for ($i = 0; $i < 1000000000; $i++){
            
        }
        $now = date("Y-m-d H:i:s");
        TMDebugUtils::debugLog("-----".$now."---");
    }
    /**
     * test soap
     */
    public function soaptestAction()
    {
        $client = new SoapClient("http://service.pandaqq.com/QiaohuSMS.asmx?WSDL",array('encoding'=>'utf8'));
//        $res = $client->SendSMS("qiaosamhu", "Q2I3A4O5S6M7S", "13564663499");
        $res = $client->SendSMS(array('name'=>'qiaosamhu', 'pwd'=>'Q2I3A4O5S6M7S', 'tel'=>'13564663499'));
        print_r($res);
    }
    /**
     * test info
     */
    public function testinfoAction(){
        $curl = new TMCurl();
        $res = $curl->sendByPost(array('name'=>'qiaosamhu', 'pwd'=>'Q2I3A4O5S6M7S','tel'=>'13564663499'),"http://service.pandaqq.com/QiaohuSMSInvokPage.aspx");
        print_r($res);
    }
    /**
     * redirect url
     */
    public function headerAction(){
        echo "xx";
        header("Location: http://xiaoshenge.iteye.com/");
        $name = $this->request->getGetParameter();
        TMFilterUtils::filterIp();
        $ts = new TMService();
        $ts->select($conditions, $select);
        $ts->update($fields, $where, $table);
        TMDebugUtils::debugLog("--");
    }
}