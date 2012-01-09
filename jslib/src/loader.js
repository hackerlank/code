document.domain = 'qq.com';
/**
 * @fileOverview JS文件加载器
 * @param {Array} urls 需要加载的模块
 * @param {Function} callback 加载完后执行的函数
 * @author bondli@tencent.com
 * @version 1.4.2
 * 
 */
var loader = function(urls, callback) {
	var win = window,
	doc = document,
	proto = 'prototype',
	head = doc.getElementsByTagName('head')[0],
	sniff = /*@cc_on!@*/1 + /(?:Gecko|AppleWebKit)\/(\S*)/.test(navigator.userAgent); // 0 - IE, 1 - O, 2 - GK/WK

	/**
	 * @name _creatNode
	 * @description 文档中创建节点
	 * @param {String} tag 需要标签
	 * @param {Object} attr 属性键值对对象
	 * @return {Object} 文档中节点
	 * @author bondli@tencent.com
	 * 
	 */
	var _createNode = function(tag, attrs) {
		var attr, node = doc.createElement(tag);
		for(attr in attrs) {
			if(attrs.hasOwnProperty(attr)) {
				node.setAttribute(attr, attrs[attr]);
			}
		}
		return node;
	};
	
	/**
	 * 载入JS文档
	 * @param {Object} 模板对象
	 * @param {Object} 载入文档后执行的函数
	 * @author bondli@tencent.com
	 * 
	 */
	var load = function(urls, callback) {
		if(this == win) {
			return new load(urls, callback);
		}
		urls = (typeof urls == 'string' ? [urls] : urls);
		this.callback = callback || function() {};
		this.queue = [];
		var node, i = len = 0, that = this;
		//获得已加载的js文件
		var scriptArr = doc.getElementsByTagName('script');
		var sList = '';
		for(var t=0;t<scriptArr.length;t++){
			sList += scriptArr[t].getAttribute('src') + ';';
		}

		for (i = 0, len = urls.length; i < len; i++) {
			this.queue[i] = 1;
			//加载前判断是否已经存在
			var subpath = (appConfig.runMode == 'production') ? 'build' : 'src';
			var file = appConfig.rootPath + subpath + '/' + urls[i] + '.js';
			if(sList.indexOf(file) == -1){
				node = _createNode('script', { type: 'text/javascript', src: file });
				head.appendChild(node);
				if(sniff) {
					node.onload = function() {
						try{
							that.__callback();
						}
						catch(e){
							alert('网络繁忙，请重新刷新!');
						}
					}
				}
				else {
					node.onreadystatechange = function() {
						if (/^loaded|complete$/.test(this.readyState)) {
							this.onreadystatechange = null;
							try{
								that.__callback();
							}
							catch(e){
								alert('网络繁忙，请重新刷新!');
							}
						}
					};
				}
			}
			else{
				that.__callback();
			}
		}
		return this;
	};
	load[proto].__callback = function() {
		if(this.queue.pop() && (this.queue == 0)) { this.callback(); }
	};
	return new load(urls, callback);
};

/**
 * @name loaderStatic
 * @description 载入静态js文件
 */
var loaderStatic = function(urls){
	urls = (typeof urls == 'string' ? [urls] : urls);
	var subpath = (appConfig.runMode == 'production') ? 'build' : 'src';
	for (i = 0, len = urls.length; i < len; i++) {
		var file = appConfig.rootPath + subpath + '/' + urls[i] + '.js';
		document.write("<scri" + "pt type=\"text/javascript\" src=\"" + file + "\"></scri" + "pt>");
	}
};

//载入autoload组件
loaderStatic(appConfig.autoload);

//启动程序，开始将要执行的js代码加载到ready中
(function() {
	var domReady = !+'\v1' ? function(f) {(function() {
			try{
				document.documentElement.doScroll('left');
			} catch (error) {
				setTimeout(arguments.callee, 0);
				return;
			};
			f();
		})();
	} : function(f) {
		document.addEventListener('DOMContentLoaded', function(){
			document.removeEventListener("DOMContentLoaded", arguments.callee, false);
			f();
		}, false);
	};
	eval(appConfig.namespace + '=domReady');
})();

$(function(){
	//执行在全局filter前面的hook
	if (typeof _onPageReadyFilterBefore == "function") {
		_onPageReadyFilterBefore();
	}
	//执行全局filter
	if (typeof _onPageReadyFilter == "function") {
		_onPageReadyFilter();
	}
	//执行在全局filter后面的hook
	if (typeof _onPageReadyFilterAfter == "function") {
		_onPageReadyFilterAfter();
	}
});