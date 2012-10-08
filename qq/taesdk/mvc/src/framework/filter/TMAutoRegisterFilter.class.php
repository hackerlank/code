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
 * TMAutoRegisterFilter
 * 处理全站QQ登录即注册
 *
 * @package sdk.mvc.src.framework.filter
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMAutoRegisterFilter.class.php 2010-12-30 by ianzhang
 */
class TMAutoRegisterFilter extends TMFilter{

	protected function _register($uin) {
		$user = TMDAOFactory::getDAO('user')->findByKey(array('FQQ' => $uin));
		if (null == $user) {    		
			$user = new TMUser();
			$user->set('FQQ', $uin);
			$user->set('FDate', Date('Y-m-d'));
			$user->set('FTime', Date('Y-m-d H:i:s'));
			$user->save();
		}
	}

	public function execute($filterChain) {
		$request = TMDispatcher::getInstance()->getRequest();
        $response = TMDispatcher::getInstance()->getResponse();
		if (TMAuthUtils::isLogin()) {
			$nameSpace = TMConfig::get('tams_id');
			$domain = TMConfig::get('domain');
			
			$uin = TMAuthUtils::getUin();
			$trackCookieName = $nameSpace . '_check_login_' . $uin;
			$isTrackChecked = $request->getCookie($trackCookieName);
			if ($isTrackChecked != 'checked') {
				TMTrackUtils::trackAction($uin, 6000101);
				$response->setCookie($trackCookieName, 'checked', 0, '/', $domain);
			}

			$registerCookieName = $nameSpace . '_check_register_' . $uin;
			$isChecked = $request->getCookie($registerCookieName);
			if ($isChecked != 'checked') {
				$this->_register($uin);
				$response->setCookie($registerCookieName, 'checked', 0, '/', $domain);
			}
		}
		$filterChain->execute();
	}
}