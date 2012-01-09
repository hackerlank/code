/**
 * @fileOverview app jslib 显示flash类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.flmo = {

	historyHash: '',
	
	histroyHashArr: [],

    init: function(){
		var hash = (!window.location.hash)? "#home" : window.location.hash;
		//改变浏览器中的hash
		window.location.hash = hash;
		//改变历史hash
		this.historyHash = hash;
		//将hash写进浏览历史
		var ret = this.addHistroy(hash);
		if(ret == true){
			//执行PV/UV的增加
			this.monitor(hash);
		}
	},

	addHistroy: function(hash){
		var in_arr = false;
		var hashArr = this.histroyHashArr;
		for(o in hashArr){
			if(o == hash){
				in_arr = true;
				//判断是否在5分钟内的点击
				var now = new Date().getTime();
				if(hashArr[hash] < now-5*60*1000){
					return true;
				}
				else{
					return false;
				}
				break;
			}
		}
		if(in_arr == false){
			this.histroyHashArr[hash] = new Date().getTime();
			return true;
		}
	},
	
	checkHash: function(){
		var curHash = window.location.hash;
		//console.log(curHash);
		if(curHash != this.historyHash){
			this.init();
		}
	},
	
	monitor: function(){
		var u = window.location.href;
		var ref = document.referrer;
		var img = new Image();
		img.src = 'http://t.l.qq.com/ping?t=m&cpid='+ appConfig.tamsid +'&url='+ escape(u) + '&ref=' + escape(ref);
	}
};

//开启全局定时器，进行扫描
var t = window.setInterval('$app.flmo.checkHash()',50);