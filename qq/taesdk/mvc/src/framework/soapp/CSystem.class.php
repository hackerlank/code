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
 * CSystem
 * 系统级别组件
 *
 * @package sdk.mvc.src.framework.soapp
 * @author  ianzhang <ianzhang@tencent.com>
 * @version CSystem.class.php 2010-8-16 by ianzhang
 */
class CSystem extends TMBaseComponent {
	/**
	 * 去判断是否有登录QQ，如果有则加入adid，用于增加uv统计的准确性
	 */
	public function adid() {
		if(TMAuthUtils::isLogin()){
			$qq = TMAuthUtils::getUin();
			$response = TMComponent::getInstance()->getResponse();
			$request = TMComponent::getInstance()->getRequest();
			
			$adidCookie = $request->getCookie("adid");
			if(empty($adidCookie)){
                $response->setCookie("adid",$qq, 0, "/", "qq.com");
			}
			
			$cookieName = TMConfig::get("namespace")."_check_login_".$qq;
			$isChecked = $request->getCookie($cookieName);
			if($isChecked != "checked")
			{
				TMTrackUtils::trackAction($qq, 6000101);
				$response->setCookie($cookieName, "checked", 0, "/", TMConfig::get("domain"));
			}
		}
	}

	/**
	 * 执行添加监控代码逻辑
	 * 监控代码在 ROOT_PATH . 'templates/toolbar.php'
	 */
	public function toolBar()
	{
		$component = TMComponent::getInstance();

		$content = $component->getResponse()->getContent();

		//$replace = "<body>\n".$toolBar."\n";
		//$content = str_replace("<body>",$replace,$content);
		$content = preg_replace_callback('/<body([^>]*)>/is', array($this, 'toolbarReplaceCallback'), $content);

		$component->getResponse()->setContent($content);
	}

	/**
	 * 正则替换toolbar的回调函数，检查body的属性中是否含有关键字'noqbar'或者'notoolbar',如果包含则不添加toolbar
	 *
	 * @param array $matches 正则匹配到的数组
	 */
	private function toolbarReplaceCallback($matches) {
		if (!empty($matches[1]) && preg_match('/no(qbar|toolbar)/i', $matches[1])) {
			return $matches[0];
		}
		$view = new TMView();
		$toolBar = $view->renderFile(array(), ROOT_PATH . "templates/toolbar.php");
		return "<body{$matches[1]}>\n{$toolBar}\n";
	}

	/**
	 * 执行添加debug bar逻辑
	 * 监控代码在 ROOT_PATH . 'templates/debugbar.php'
	 * 
	 * @param array $data
	 */
	public function debugBar($data)
	{
		$component = TMComponent::getInstance();

		$content = $component->getResponse()->getContent();

		//$replace = "<body>\n".$toolBar."\n";
		//$content = str_replace("<body>",$replace,$content);
		$content = preg_replace_callback('/<body([^>]*)>/is', array($this, 'debugbarReplaceCallback'), $content);

		$component->getResponse()->setContent($content);
	}

	/**
	 * 正则替换debugbar的回调函数
	 *
	 * @param array $matches 正则匹配到的数组
	 */
	private function debugbarReplaceCallback($matches) {
		$view = new TMView();
		$debugBar = $view->renderFile(array(), ROOT_PATH . "templates/debugbar.php");
		return "<body{$matches[1]}>\n{$debugBar}\n";
	}

	/**
	 * 执行添加监控代码逻辑
	 * 监控代码在 ROOT_PATH . 'templates/track.php'
	 */
	public function track()
	{
		$component = TMComponent::getInstance();
		$needTrack = $component->getResponse()->getNeedTrackStatus();
		if($needTrack && $_ENV["SERVER_TYPE"] != "test")
		{
			$view = new TMView();
			$pageTrack = $view->renderFile(array(), ROOT_PATH . "templates/track.php");
			$content = $component->getResponse()->getContent();
			$replace = "\n".$pageTrack."\n</body>";
			$content = str_replace("</body>",$replace,$content);
			$component->getResponse()->setContent($content);
		}
	}

	/**
	 * 执行登录即注册的操作
	 * @param array $data
	 */
	public function loginBeRegistered($data)
	{
		$component = TMComponent::getInstance();
		$request = $component->getRequest();
		$this->set(__CLASS__."_".__FUNCTION__, false);
		if(TMAuthUtils::isLogin())
		{
			$qq = TMAuthUtils::getUin();
			$input = $this->parseFromParams($data["input"]);
			$keyPrefix = isset($input["keyPrefix"]) ? $input["keyPrefix"] : TMConfig::get("namespace")."_check_register_";
			$dataAlias = isset($input["dataAlias"]) ? $input["dataAlias"] : "user";
			$isChecked = $request->getCookie($keyPrefix.$qq);
			if($isChecked != "checked")
			{
				$response = $component->getResponse();
				$user = TMDAOFactory::getDAO($dataAlias)->findByKey(array("FQQ" => $qq));
				//注册流程
				//Tbl_User
				if($user == null)
				{
					$userinfo = TMAuthUtils::getUserInfo();
					if($userinfo['nickname'])
					{
						$nickname = $userinfo['nickname'];
					}
					else{
						$nickname = $qq;
					}
					 
					$user = new TMObject($dataAlias);
					$user->set("FQQ", $qq);
					//$user->set("FNick", $nickname);
					$user->set("FTime", Date("Y-m-d H:i:s"));
					$user->set("FDate", Date("Y-m-d"));
					$user->set("FIp", TMUtil::getClientIp());
					$user->save();

					$this->set(__CLASS__."_".__FUNCTION__, true);
				}

				//Tbl_Score
				$dataScoreAlias = $input["dataScoreAlias"];
				if(!empty($dataScoreAlias))
				{
					$score = TMDAOFactory::getDAO($dataScoreAlias)->findByKey(array("FQQ" => $qq));
					if($score == null)
					{
						$scoreinfo = TMAuthUtils::getUserInfo();
						if($scoreinfo['nickname'])
						{
							$nickname = $scoreinfo['nickname'];
						}
						else{
							$nickname = $qq;
						}
						$score = new TMObject($dataScoreAlias);
						$score->set("FQQ", $qq);
						$score->set("FTime", Date("Y-m-d H:i:s"));
						$score->set("FDate", Date("Y-m-d"));
						$score->save();

						$this->set(__CLASS__."_".__FUNCTION__, true);
					}
				}
				 
				//Tbl_UserProfile
				$dataProfileAlias = $input["dataProfileAlias"];
				if(!empty($dataScoreAlias))
				{
					$profile = TMDAOFactory::getDAO($dataProfileAlias)->findByKey(array("FQQ" => $qq));
					if($profile == null)
					{
						$profileinfo = TMAuthUtils::getUserInfo();
						if($profileinfo['nickname'])
						{
							$nickname = $profileinfo['nickname'];
						}
						else{
							$nickname = $qq;
						}
						$profile = new TMObject($dataProfileAlias);
						$profile->set("FQQ", $qq);
						$profile->set("FTime", Date("Y-m-d H:i:s"));
						$profile->set("FDate", Date("Y-m-d"));
						$profile->save();
						 
						$this->set(__CLASS__."_".__FUNCTION__, true);
					}
				}

				$response->setCookie($keyPrefix.$qq, "checked", 0, "/", TMConfig::get("domain"));
			}
		}
	}
}
?>