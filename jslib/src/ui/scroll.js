/**
 * @fileOverview app jslib UI类图片滚动组件
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.scroll = {
	/**
	 * 滚动对象
	 */
	_obj: null,
	
	/**
	 * 全局定时器
	 */
	_timer: null,
	
	/**
	 * 自动滚动全局标识
	 */
	_autoFlag: null,
	
	/**
	 * 滚动锁
	 */
	_moveLock: false,
	
	/**
	 * 未知
	 */
	_comp:0,
	
	/**
	 * 默认参数
	 */
	options: {
		'speed':1,				//翻滚速度
		'space':20,				//每次滚动宽度
		'scrollWidth':642,		//翻滚宽度
		'interval':3000,		//翻滚间隔
		'fill':1,				//是否整体移位
		'way':'right',			//移动方向
		'auto':true				//是否自动滚
	},
	
	/**
	 * @name _extend
	 * @description 参数拓展
	 * @param {Object} obj 原来对象
	 * @param {Object} options 附加选项
	 * @return {Object} 返回新的参数对象
	 * @author bondli@tencent.com
	 * 
	 */
	_extend: function(obj,options){
		for(var o in options){
			obj[o] = options[o];
		}
		return obj;
	},
	
	/**
	 * @name _getId
	 * @description 根据ID获得对象
	 * @param {String} id
	 * @return {Object} 返回对象
	 * @author bondli@tencent.com
	 * 
	 */
	_getId: function(id){
		return typeof id == 'object' ? id : document.getElementById(id);
	},

	/**
	 * @name init
	 * @description 初始化
	 * @param {Object} scollid 滚动图片容器的ID
	 * @param {Object} param 滚动效果控制参数
	 * @author bondli@tencent.com
	 * 
	 */
	init: function(scollid,param){
		var self = this;
		this._obj = this._getId(scollid);
		this._options = this._extend(this._options,param);
		this._getId("list2").innerHTML = this._getId("list1").innerHTML;
		this._obj.scrollLeft = this._options.fill >= 0 ? this._options.fill : this._getId('list1').scrollWidth - Math.abs(this._options.fill);
    	this._obj.onmouseover = function(){
        	clearInterval(self._autoFlag);
    	};
    	this._obj.onmouseout = function(){
       		self.goAuto();
    	};
		if(this._options.auto == true) this.goAuto();
	},

	/**
	 * @name goBack
	 * @description 点击向后翻
	 * @author bondli@tencent.com
	 * 
	 */
	goBack: function(){
		if (this._moveLock) return;
		clearInterval(this._autoFlag);
    	this._moveLock = true;
    	this._options.way = "left";
    	this._timer = setInterval('$app.scroll.goBackScroll();', this._options.speed);
	},

	/**
	 * @name goNext
	 * @description 点击向前翻
	 * @author bondli@tencent.com
	 * 
	 */
	goNext: function(){
		clearInterval(this._timer);
		if (this._moveLock) return;
		clearInterval(this._autoFlag);
		this._moveLock = true;
		this._options.way = "right";
		this.goNextScroll();
    	this._timer = setInterval('$app.scroll.goNextScroll();', this._options.speed);
	},
	
	/**
	 * @name goBackStop
	 * @description 停止后翻
	 * @author bondli@tencent.com
	 * 
	 */
	goBackStop: function(){
		if (this._options.way == "right") return;
    	clearInterval(this._timer);
		if((this._obj.scrollLeft - this._options.fill) % this._options.scrollWidth != 0) {
        	this._comp = this._options.fill - (this._obj.scrollLeft % this._options.scrollWidth);
        	this.compScr();
    	}
    	else {
        	this._moveLock = false;
    	}
    	this.goAuto();
	},
	
	/**
	 * @name goNextStop
	 * @description 停止前翻
	 * @author bondli@tencent.com
	 * 
	 */
	goNextStop: function(){
		if (this._options.way == "left") return;
    	clearInterval(this._timer);
    	if (this._obj.scrollLeft % this._options.scrollWidth - (this._options.fill >= 0 ? this._options.fill : this._options.fill + 1) != 0) {
        	this._comp = this._options.scrollWidth - this._obj.scrollLeft % this._options.scrollWidth + this._options.fill;
        	this.compScr();
    	}
    	else {
       		this._moveLock = false;
    	}
    	this.goAuto();
	},
	
	/**
	 * @name goBackScroll
	 * @description 向后翻滚
	 * @author bondli@tencent.com
	 * 
	 */
	goBackScroll: function(){
		if (this._obj.scrollLeft <= 0) {
        	this._obj.scrollLeft = this._obj.scrollLeft + this._getId('list1').offsetWidth;
    	}
    	this._obj.scrollLeft -= this._options.space;
	},
	
	/**
	 * @name goNextScroll
	 * @description 向前翻滚
	 * @author bondli@tencent.com
	 * 
	 */
	goNextScroll: function(){
		if (this._obj.scrollLeft >= this._getId('list1').scrollWidth) {
        	this._obj.scrollLeft = this._obj.scrollLeft - this._getId('list1').scrollWidth;
    	}
    	this._obj.scrollLeft += this._options.space;
	},
	
	/**
	 * @name goAuto
	 * @description 自动翻滚
	 * @author bondli@tencent.com
	 * 
	 */
	goAuto: function(){
		clearInterval(this._autoFlag);
    	this._autoFlag = setInterval('$app.scroll.goNext();$app.scroll.goNextStop();', this._options.interval);
	},
	
	/**
	 * @name compScr
	 * @description 图片滚动控制
	 */
    compScr: function(){
        if (this._comp == 0) {
            this._moveLock = false;
            return;
        }
        var num, TempSpeed = this._options.speed, TempSpace = this._options.space;
        if (Math.abs(this._comp) < this._options.scrollWidth / 2) {
            TempSpace = Math.round(Math.abs(this._comp / this._options.space));
            if (TempSpace < 1)TempSpace = 1;
        }
        if (this._comp < 0) {
            if (this._comp < -TempSpace) {
                this._comp += TempSpace;
                num = TempSpace;
            }
            else {
                num = - this._comp;
                this._comp = 0
            }
            this._obj.scrollLeft -= num;
            setTimeout('$app.scroll.compScr()', TempSpeed);
        }
        else {
            if (this._comp > TempSpace) {
                this._comp -= TempSpace;
                num = TempSpace;
            }
            else {
                num = this._comp;
                this._comp = 0;
            }
            this._obj.scrollLeft += num;
            setTimeout('$app.scroll.compScr()', TempSpeed);
        }
    }
};
