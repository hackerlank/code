/**
 * @fileOverview app jslib 工具类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.util = {
	/**
	 * @name getURLParam
	 * @description 获取URL的Get参数
	 * @param {String} strParamName url参数名
	 * @return {String} 指定参数的值
	 * @author bondli@tencent.com
	 * 
	 */
	getURLParam: function(strParamName){
		var strReturn = "";
		var strHref = window.location.href;
		var bFound = false;

		var cmpstring = strParamName + "=";
		var cmplen = cmpstring.length;

		if (strHref.indexOf("?") > -1) {
			var strQueryString = strHref.substr(strHref.indexOf("?") + 1);
			var aQueryString = strQueryString.split("&");
			for (var iParam = 0; iParam < aQueryString.length; iParam++) {
				if (aQueryString[iParam].substr(0, cmplen) == cmpstring) {
					var aParam = aQueryString[iParam].split("=");
					strReturn = aParam[1];
					bFound = true;
					break;
				}
			}
		}
		if (bFound == false) {
			return null;
		}

		return strReturn;
	},

	/**
	 * @name trim
	 * @description 去除字符串首尾的空白字符
	 * @param {String} str 输入的字符串
	 * @return {String} 去掉首末空白字符
	 * @author bondli@tencent.com
	 * 
	 */
	trim: function(str){
		return str.replace(/^\s*(.*?)\s*$/, "$1");
	},

	/**
	 * @name cookie
	 * @description cookie控制函数
	 * @param {String} name cookie名
	 * @param {String} value cookie值
	 * @param {Object} options 参数对象
	 * @author bondli@tencent.com
	 * 
	 */
	cookie: function(name, value, options){
		if (typeof value != 'undefined') { // name and value given, set cookie
			options = options || {};
			if (value === null) {
				value = '';
				options.expires = -1;
			}
			var expires = '';
			if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
				var date;
				if (typeof options.expires == 'number') {
					date = new Date();
					date.setTime(date.getTime() + (options.expires * 1000));
				}
				else {
					date = options.expires;
				}
				expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
			}
			var path = options.path ? '; path=' + options.path : '';
			var domain = options.domain ? '; domain=' + options.domain : '';
			var secure = options.secure ? '; secure' : '';
			document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
		}
		else { // only name given, get cookie
			var cookieValue = null;
			if (document.cookie && document.cookie != '') {
				var cookies = document.cookie.split(';');
				for (var i = 0; i < cookies.length; i++) {
					var cookie = $app.util.trim(cookies[i]);
					// Does this cookie string begin with the name we want?
					if (cookie.substring(0, name.length + 1) == (name + '=')) {
						cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
						break;
					}
				}
			}
			return cookieValue;
		}
	},

	/**
	 * @name copyToClipboard
	 * @description 将txt拷贝到剪贴板
	 * @param {String} txt 要拷贝的内容
	 * @return string 如果拷贝成功返回空字符串，否则返回错误提示信息
	 * @author bondli@tencent.com
	 */
	copyToClipboard: function(txt) {
		if (window.clipboardData) {
			window.clipboardData.setData("Text", txt);
			return '';
		}
		else {
			if (navigator.userAgent.indexOf("Opera") != -1) {
				alert('你的浏览器不支持复制，请用IE或者Firefox来完成复制!');
				return '';
			}
			else {
				if (window.netscape) {
					try {
						netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
					}
					catch (e) {
						return "您的firefox安全设置限制您进行剪贴板操作，请打开'about:config'将signed.applets.codebase_principal_support'设置为true'之后重试";
					}
					var clip = Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);
					if (!clip) {
						return '';
					}
					var trans = Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
					if (!trans) {
						return '';
					}
					trans.addDataFlavor('text/unicode');
					var str = new Object();
					var len = new Object();
					var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
					var copytext = txt;
					str.data = copytext;
					trans.setTransferData("text/unicode", str, copytext.length * 2);
					var clipid = Components.interfaces.nsIClipboard;
					if (!clip) {
						return '';
					}
					clip.setData(trans, null, clipid.kGlobalClipboard);
					return '';
				}
				else {
					alert('你的浏览器不支持复制，请用IE或者Firefox来完成复制!');
				}
			}
		}
	},

	/**
	 * @name favorite
	 * @description 加入收藏夹
	 * @param {String} sUrl 要收藏的url
	 * @param {String} sTitle 要收藏的url的描述
	 * @author bondli@tencent.com
	 * 
	 */
	favorite: function(sUrl,sTitle){
		if (typeof sUrl == 'undefined') {
			sUrl = window.location.href;
		}
		if (typeof sTitle == 'undefined') {
			sTitle = window.title;
		}
		try{
			window.external.addFavorite(sUrl, sTitle);
		}
		catch (e){
			try{
				window.sidebar.addPanel(sTitle, sUrl, "");
			}
			catch (e){
				alert("加入收藏失败，请使用Ctrl+D进行添加");
			}
		}
	},
	
	/**
	 * @name log
	 * @description 浏览器端调试日志
	 * @param {String} c 要输出的信息
	 * @author bondli@tencent.com
	 * 
	 */
	log: function(c){
		if ($app.B.name == 'msie') {
			return;
		}
		else {
			console.log(c);
		}
	},

	/**
	 * @name _createNode
	 * @description 创建子节点
	 * @param {String} tag 标签名
	 * @param {String} attrs 属性
	 * 
	 */
	_createNode: function(tag, attrs) {
		var attr, node = document.createElement(tag);
		for(attr in attrs) {
			if(attrs.hasOwnProperty(attr)) {
				node.setAttribute(attr, attrs[attr]);
			}
		}
		return node;
	},

	/**
	 * @name track
	 * @description 添加PV/UV监测代码
	 * 
	 */
	track: function() {
		var u = "http://t.l.qq.com/ping?t=m&cpid=" + appConfig.tamsid +
			"&url=" + escape(window.location.href) + "&ref=" + escape(document.referrer);
		var s = "width:1px;height:1px;position:absolute;top:1px;left:1px;";
		var n = this._createNode('img', {'src':u,'style':s});
		document.body.appendChild(n);
	},

	/**
	 * @name toolbar
	 * @description 添加toolbar到页面上
	 * @param {String} version toolbar版本，默认是2.2
	 * 
	 */
	toolbar: function(version) {
		if(typeof version == 'undefined') {
			var version = '2.2';
		}
		//先加载toolbar的css
		$app.css('http://toolbar.tae.qq.com/'+version+'/css/default.css');
		//加载toolbar的js
		var u = 'http://toolbar.tae.qq.com/?v='+version+'&f=jslib.1.4&a='+appConfig.appid+
			'&t='+appConfig.tamsid+'&r='+appConfig.domain+'&s=text';
		var n = this._createNode('script', {'src':u,'type':'text/javascript','charset':'utf-8'});
		document.body.appendChild(n);
	}
};