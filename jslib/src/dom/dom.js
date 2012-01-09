/**
 * @fileOverview app jslib DOM操作类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.dom = {
	/**
	 * @name hasClass
	 * @description 判断节点是否拥有某个样式
	 * @param {Object} el dom对象
	 * @param {String} className 样式名称
	 * @return {Boolean} 0:不存在 1:存在
	 * @author bondli@tencent.com
	 *
	 */
	hasClass: function(el, className){
		return className && (' ' + el.className + ' ').indexOf(' ' + className + ' ') > -1;
	},
	
	/**
	 * @name addClass
	 * @description给节点添加某个样式
	 * @param {Object} el dom对象
	 * @param {String} className 样式名称
	 * @author bondli@tencent.com
	 * 
	 */
	addClass: function(el, className) {
		if (el.className === '') {
			el.className = className;
		}else if (el.className !== '' && !this.hasClass(el, className)) {
			el.className = el.className + ' ' + className;
		}
	},
	
	/**
	 * @name removeClass
	 * @description 移除节点的某个样式
	 * @param {Object} el dom对象
	 * @param {String} className 样式名称
	 * @author bondli@tencent.com
	 * 
	 */
	removeClass: function(el, className) {
		if (this.hasClass(el, className)) {
			el.className = (' ' + el.className + ' ').replace(' ' + className + ' ', ' ').replace(/^ | $/g,'');
		}
	},

	/**
	 * @name getElementsByClassName
	 * @description 根据标签的样式名抓取节点对象
	 * @param {String} className 样式名称
	 * @param {String} tag 标签
	 * @param {Object} root 上级节点对象
	 * @return {Array} 节点数组
	 * @author bondli@tencent.com
	 * 
	 */
	getElementsByClassName: function(className, tag, root) {
		if (!root) {return [];}
		var nodes = [],
		elements = root.getElementsByTagName(tag);

		for (var i = 0, len = elements.length; i < len; i++) {
			if ( this.hasClass(elements[i], className) ) {
				nodes[nodes.length] = elements[i];
			}
		}

		return nodes;
	},
	
	/**
	 * @name getPreviousSibling
	 * @description 抓取前一个兄弟节点
	 * @param {Object} node 节点对象
	 * @return {Object} 节点对象
	 * @author bondli@tencent.com
	 * 
	 */
	getPreviousSibling: function(node) {
		while (node) {
			node = node.previousSibling;
			if ( node && node.nodeType == 1 ) {
				return node;
			}
		}
		return null;
	},
	
	/**
	 * @name get NextSibling
	 * @description 抓取下一个兄弟节点
	 * @param {Object} node 节点对象
	 * @return {Object} 节点对象
	 * @author bondli@tencent.com
	 * 
	 */
	getNextSibling: function(node) {
		while (node) {
			node = node.nextSibling;
			if ( node && node.nodeType == 1 ) {
				return node;
			}
		}
		return null;
	},
	
	/**
	 * @name getChildren
	 * @description 抓取直系子节点
	 * @param {Object} node 节点对象
	 * @return {Object} 节点对象
	 * @author bondli@tencent.com
	 * 
	 */
	getChildren: function(node) {
		var nodes = node.children || node.childNodes, i, iLen, arrNew = [];
		iLen = nodes.length;
		if (iLen === 0) {return arrNew;}
		if (node.children) {
			return nodes;
		}else {
			for (i=0;i<iLen;i++) {
				if (nodes[i] && (nodes[i].nodeType != 1 || (nodes[i].nodeType == 1 && nodes[i].parentNode != node))) {	//delete text node and not direct childs
					continue;
				}
				arrNew[arrNew.length] = nodes[i];
			}
		}

		return arrNew;
	},
	
	/**
	 * @name isChild
	 * @description 判断节点sunObj是否为节点parentObj的子孙节点
	 * @param {Object} sunObj 子节点对象
	 * @param {Object} parentObj 父节点对象
	 * @return {Boolean}
	 * @author bondli@tencent.com
	 * 
	 */
	isChild: function(sunObj, parentObj) {
		if (!sunObj || !parentObj) {return 'we need sun element object and parent element object.';}

		if (parentObj.tagName && parentObj.tagName.toLowerCase() == 'body') {
			return true;
		}
		while (sunObj && sunObj.tagName && sunObj.tagName.toLowerCase() != 'body') {
			if (sunObj.parentNode == parentObj) {
				return true;
			}
			sunObj = sunObj.parentNode;
		}
		return false;
	},
	
	/**
	 * @name getStyle
	 * @description 获得元素指定样式的值
	 * @param {Object} dom dom对象
	 * @param {String} stylename style属性，如width
	 * @param {Boolean} bNum 是否返回整型，1：返回没有单位的整型数字
	 * @return {String} 样式的值
	 * @author bondli@tencent.com
	 */
	getStyle: function(dom,stylename,bNum) {
		if(dom.currentStyle){
			return bNum ? parseFloat(dom.currentStyle[stylename].replace(/px|pt|em/ig,'')) : dom.currentStyle[stylename];
		}else{
			if(dom.style[stylename]) {
				return bNum ? parseFloat(dom.style[stylename].replace(/px|pt|em/ig,'')) : dom.style[stylename]; 
			}
			else{
				return bNum ? parseFloat(window.getComputedStyle(dom,null).getPropertyValue(stylename).replace(/px|pt|em/ig,'')) : window.getComputedStyle(dom,null).getPropertyValue(stylename);
			}
		}
	},
	/**
	 * @name setStyle
	 * @description 设置元素指定样式的值
	 * @param {Object} dom dom对象
	 * @param {String} stylename style属性，如width
	 * @author bondli@tencent.com
	 */
	setStyle: function(dom,stylename,stylevalue) {
		if(typeof(dom) == 'String'){
			dom = $app.G(dom);
		}
		dom.style[stylename] = stylevalue;
	}
};