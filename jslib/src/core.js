/**
 * @fileOverview app jslib 核心类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
var $app = {
	/**
	 * @description 版本号定义
	 * @field
	 */
	version: '1.4.2',
	
	/**
	 * @description 浏览器信息
	 * @field
	 */
	B : {
		version : (window.navigator.userAgent.toLowerCase().match(/.+(?:pe6?|or|ox|it|ra|ie|rv|me)[\/: ]([\d.]+)/) || [])[1],
		name    : /(netscape|firefox|opera|msie|safari|konqueror|chrome)/.test(window.navigator.userAgent.toLowerCase()) ? RegExp.$1 : 
			(/webkit/.test(window.navigator.userAgent.toLowerCase()) ? "safari" : 
			(/mozilla/.test(window.navigator.userAgent.toLowerCase()) ? "mozilla" : "unknown"))
	},
	
	/**
	 * @name G
	 * @description 根据id获得对象，document.getElementById的简写
	 * @param {String} id 对象的ID名称
	 * @return {Object} dom对象
	 * @author bondli@tencent.com
	 * 
	 */
	G: function(id) {
		return 'string' == typeof id ? document.getElementById(id) : id;
	},
	
	/**
	 * @name addEvent
	 * @description 为对象添加监听事件
	 * @param {Object} obj 对象或者ID名称
	 * @param {String} type 事件类型
	 * @param {Function} fn 为该对象绑定的函数
	 * @author bondli@tencent.com
	 * 
	 */
	addEvent : function( obj, type, fn ) {
		var objId = obj;
		if(typeof(obj) == 'string'){
			obj = document.getElementById(obj);	
		}
		else{
			objId = obj.id;
		}
		//防止多次注册事件
		if(this._addEventArr[objId] == 1){
			return;
		}
		else {
			this._addEventArr[objId] = 1;
		}
        if (obj.addEventListener) {
			obj.addEventListener(type, fn, false);
		}
        else if (obj.attachEvent){
			obj.attachEvent('on' + type, function() { 
				return fn.apply(obj, new Array(window.event));
			});
		}
	},

	_addEventArr : [],
	
	/**
	 * @name now
	 * @description 返回当前时间，常用AJAX后面，防止ie6下缓存
	 * @return {Number} 当前时间
	 * @author bondli@tencent.com
	 * 
	 */
	now : function() {
		return new Date().getTime();
	},
	
	/**
	 * @name css
	 * @description 加载CSS文件
	 * @param {String} file 文件路径
	 * @author bondli@tencent.com
	 * 
	 */
	css : function(file) {
		var head = document.getElementsByTagName('head').item(0);
		var style = document.createElement('link');
		style.href = file;
		style.rel = 'stylesheet';
		style.type = 'text/css';
		head.appendChild(style);
	}
};