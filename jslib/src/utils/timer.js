/**
 * @fileOverview app jslib 倒数计时器类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.timer = {
	/**
	 * @description 内部定时器
	 * 
	 */
	_timer: null,
	
	/**
	 * @description 显示倒计时的容器ID
	 * 
	 */
	_showdiv: null,
	
	/**
	 * @description 结束时间串，如2010-10-01 09:00:00
	 */
	_timeline: null,
	
	/**
	 * @name init
	 * @description 初始化定时器函数
	 * @param {String} id 显示倒计时的容器ID
	 * @param {String} timeline 结束时间串
	 * @author bondli@tencent.com
	 * 
	 */
	init: function(id,timeline){
		this._setSd(id);
		this._setTl(timeline);
		if (!this._timer) {
			this._timer = window.setInterval('$app.timer.run()',1000);
		}
	},
	
	/**
	 * @name _setSd
	 * @description 设置显示倒计时的容器ID
	 * @param {String} id
	 * @author bondli@tencent.com
	 * 
	 */
	_setSd: function(id){
		this._showdiv = id;
	},
	
	/**
	 * @name _getSd
	 * @description 获得显示倒计时的容器ID
	 * @author bondli@tencent.com
	 * 
	 */
	_getSd: function(){
		return this._showdiv;
	},
	
	/**
	 * @name _setTl
	 * @description 设置终点时间
	 * @param {String} tl
	 * @author bondli@tencent.com
	 * 
	 */
	_setTl: function(tl){
		this._timeline = tl;
	},

	/**
	 * @name _getTl
	 * @description 获得终点时间
	 * @author bondli@tencent.com
	 * 
	 */
	_getTl: function(){
		return this._timeline;
	},
	
	/**
	 * @name run
	 * @description 执行倒计时
	 * @author bondli@tencent.com
	 * 
	 */
	run: function(){
		deadline = new Date(this._getTl().replace(/-/g,"/")); //活动开始时间
		var now = new Date();
		var diff = -480 - now.getTimezoneOffset(); //是北京时间和当地时间的时间差
		var leave = (deadline.getTime() - now.getTime()) + diff*60000;
		if(leave > 0){
			var day = Math.floor(leave / (1000 * 60 * 60 * 24));
			var hour = Math.floor(leave / (1000*3600)) - (day * 24);
			var minute = Math.floor(leave / (1000*60)) - (day * 24 *60) - (hour * 60);
			var second = Math.floor(leave / (1000)) - (day * 24 *60*60) - (hour * 60 * 60) - (minute*60);
			//小时、分钟、秒前面加上0
			hour = hour<10 ? '0' + hour : hour;
			minute = minute<10 ? '0' + minute : minute;
			second = second<10 ? '0' + second : second;
			var sid = this._getSd();
			if(day > 0){
				document.getElementById(sid).innerHTML = ('距离活动开始还有：' + day + '天' + hour + ':' + minute + ':' + second);
			}
			else{
				document.getElementById(sid).innerHTML = ('距离活动开始还有：' + hour + ':' + minute + ':' + second);
			}
		}
		else{
			clearInterval(this._timer);
		}
	}
};
