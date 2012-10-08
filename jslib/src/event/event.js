/**
 * @fileOverview app jslib 事件处理类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.event = {
	/**
	 * @name stopEvent
	 * @description 阻止事件默认行为和事件冒泡
	 * @param {Object} ev 事件句柄
	 * @author bondli@tencent.com
	 * 
	 */
	stopEvent: function(ev) {
		this.stopPropagation(ev);
		this.preventDefault(ev);
	},
	
	/**
	 * @name stopPropagation
	 * @description 阻止事件冒泡
	 * @param {Object} ev 事件句柄
	 * @author bondli@tencent.com
	 * 
	 */
	stopPropagation: function(ev) {
		if (ev.stopPropagation) {
			ev.stopPropagation();
		} else {
			ev.cancelBubble = true;
		}
	},
	
	/**
	 * @name stopPropagation
	 * @description 阻止事件默认行为
	 * @param {Object} ev 事件句柄
	 * @author bondli@tencent.com
	 * 
	 */
	preventDefault: function(ev) {
		if (ev.preventDefault) {
			ev.preventDefault();
		} else {
			ev.returnValue = false;
		}
	},

	/**
	 * @name getEvent
	 * @description 获得事件名称
	 * @param {Object} e 事件句柄
	 * @author bondli@tencent.com
	 * 
	 */
	getEvent: function(e) {
		var ev = e || window.event;
		if (!ev) {
			var c = this.getEvent.caller;
			while (c) {
				ev = c.arguments[0];
				if (ev && Event == ev.constructor) {
					break;
				}
				c = c.caller;
			}
		}
		return ev;
	},
	
	/**
	 * @name getTarget
	 * @description 获取事件触发的对象
	 * @param {Object} ev 事件句柄
	 * @param {Object} resolveTextNode
	 * @return {Object} dom对象
	 * @author bondli@tencent.com
	 * 
	 */
	getTarget: function(ev, resolveTextNode) {
		var t = ev.target || ev.srcElement;
		return this.resolveTextNode(t);
	},

	/**
	 * @name getXY
	 * @description 获取event对象触发的坐标
	 * @param {Object} e 事件句柄
	 * @return {Array} 出发事件的对象坐标
	 * @author bondli@tencent.com
	 * 
	 */
	getXY: function(e) {
		return {x:e.pageX ? e.pageX : e.clientX + document.documentElement.scrollLeft, y:e.pageY ? e.pageY : e.clientY + document.documentElement.scrollTop};
	}
};