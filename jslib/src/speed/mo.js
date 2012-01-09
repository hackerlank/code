/**
 * @fileOverview app jslib 运营速度监测类
 * @example <script>mo.append('full_screen');</script>
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.mo = {
	/**
	 * 上报URL
	 */
	mo_ping_url : 'http://dp3.qq.com/play/',
	
	/**
	 * 获取用户userAgent
	 */
	getUA : function(){
		var o = {ie:0,opera:0,gecko:0,webkit:0,mobile:null};
		var ua = navigator.userAgent;
		if((/KHTML/).test(ua)){
			o.webkit = 1;
		}
		var m = ua.match(/AppleWebKit\/([^\s]*)/);
		if(m&&m[1]){
			o.webkit = parseFloat(m[1]);
			if(/ Mobile\//.test(ua)){
				o.mobile = "Apple";
			}
			else{
				m = ua.match(/NokiaN[^\/]*/);
				if(m){
					o.mobile = m[0];
				}
			}
		}
		if(!o.webkit){
			m = ua.match(/Opera[\s\/]([^\s]*)/);
			if(m&&m[1]){
				o.opera = parseFloat(m[1]);
				m = ua.match(/Opera Mini[^;]*/);
				if(m){
					o.mobile = m[0];
				}
			}
			else{
				m = ua.match(/MSIE\s([^;]*)/);
				if(m&&m[1]){
					o.ie = parseFloat(m[1]);
				}
				else{
					m = ua.match(/Gecko\/([^\s]*)/);
					if(m){
						o.gecko = 1;
						m = ua.match(/rv:([^\s\)]*)/);
						if(m&&m[1]){
							o.gecko = parseFloat(m[1]);
						}
					}
				}
			}
		}
		for (var i in o) {
			if (o[i] != 0) {
				return i + o[i];
			}
		}
	},
	
	/**
	 * 截取时间
	 * @param {Object} num
	 */
	cutTime : function(num) {
		var s = (num + '');
		return s.substring(s.length - 6);
	},
	
	/**
	 * 当前站点域名
	 */
	domain : function() {
		s = location.href;
		try {
			var m = s.match(/http:\/\/([^/]*)\/|$/i);
			return m[1];
		}
		catch (e) {
			return '';
		}
	},
	
	/**
	 * 起始点
	 */
	QosS: 0,
	
	/**
	 * 上报性能函数
	 */
	ping : function(){
		var img = new Image(1,1);
		var sUrl = this.mo_ping_url+'?ua='+this.getUA()+'&domain='+this.domain()+'&QosS='+this.QosS;
		var flag = false;
		for (var i in this.stamps) {
			if (this.stamps[i]) {
				sUrl += '&'+i+'=' + this.stamps[i];
				var flag = true;
			}
		}
		if(flag == false) return;
		img.src = sUrl;
		return this;
	},
	
	/**
	 * 贴点对象定义
	 */
	stamps : [],
	
	/**
	 * 初始化函数
	 */
	init: function(){
		this.QosS = this.cutTime(new Date().getTime());
		//console.log(this.QosS);
	},
	
	/**
	 * 捕获贴点时间
	 * @param {Object} flag
	 */
	append : function(flag){
		if(flag == 'first_screen' || flag == 'full_screen'){
			this.stamps[flag] = this.cutTime(new Date().getTime());
			//console.log(this.stamps[flag]);
		}
	}
};
$app.mo.init();
$app.addEvent(window, 'load', function(){
	$app.mo.ping();
});