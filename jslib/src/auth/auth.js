/**
 * @fileOverview app jslib 验证登录类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.auth = {
	/**
	 * 是QQ登录还是其他形式的登录
	 */
    _LoginType: appConfig.runMode == 'dev' ? 'dev' : 'qq',
    
	/**
	 * @name login
	 * @description 登录函数
	 * @param {Object} options 登录参数对象
	 * @author bondli@tencent.com
	 * 
	 */
	login: function(options) {
		loader('auth/'+ this._LoginType +'login',function(){
			$app.login.loginQQ(options);
		});
	},

	/**
	 * @name logout
	 * @description 注销函数
	 * @param {Object} options 注销参数对象
	 * @author bondli@tencent.com
	 * 
	 */
	logout: function(options) {
		loader('auth/'+ this._LoginType +'login',function(){
			$app.login.logoutQQ(options);
		});
	},

	/**
	 * @name relogin
	 * @description 重新登录
	 * @param {Object} 登录参数对象
	 * @author bondli@tencent.com
	 * 
	 */
	relogin: function(options) {
		this.logout();
		this.login(options);
	},

	/**
	 * @name isLogin
	 * @description 判断是否登录
	 * @return {Boolean} 是否登录
	 * @author bondli@tencent.com
	 * 
	 */
	isLogin: function() {
		if(this._LoginType == 'other'){
			return true;
		}
		else{
			var uin, skey;
			uin  = $app.util.cookie("uin");
			skey = $app.util.cookie("skey");
			if (uin && uin.length>4 && skey && skey.length>0)
			{
				return true;
			}
			return false;
		}
	},

	/**
	 * @name getLoginInfo
	 * @description 获取当前登录者的QQ信息
	 * @return {Object} 登录者信息的对象
	 * @author bondli@tencent.com
	 * 
	 */
	getLoginInfo: function(){
		var _uin = $app.util.cookie("uin");
		_uin = _uin.substr(1);
		_uin++;
		_uin--;
		return {uin:_uin};
	},
	
	/**
	 * @name getQQNum
	 * @description 获得登录的QQ号码
	 * @return {Number} 登录者QQ号码
	 * @author bondli@tencent.com
	 * 
	 */
	getQQNum: function() {
		var _uin = $app.util.cookie("uin");
		_uin = _uin.substr(1);
		_uin++;
		_uin--;
		return _uin;
	}
};