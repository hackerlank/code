/**
 * @fileOverview app jslib 弹出层类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.modal = {
	_temp: {},

	/**
	 * @name show
	 * @description 显示弹出层
	 * @param {String} file 文件或者DOM对象的ID
	 * @param {String} type 加载弹出内容的方式 file，iframe，ID
	 * @param {Object} option 弹出窗大小控制选项
	 * @param {Boolean} isClose 是否出现关闭按钮
	 * @author bondli@tencent.com
	 * 
	 */
	show: function(file,type,option,isClose){
		var action = file ? true : false;
		if (!action) {
			alert('$app.modal参数错误!');return false;
		}
		if(isClose == null) isClose = true;
		//创建遮罩层
		if (!this._temp.popMask) this._createMask();
		//创建弹出层
		if (!this._temp.popMain) {
			this._createMain();
			this._temp.popClose = false;
		}
		var popMain = this._temp.popMain;
		var self = this;
		if(type=='iframe'){		//iframe方式加载
			option = this._extend({'width':'300px','height':'300px'},option);
			popMain.innerHTML = "<iframe id='m-ifr' src=\"\" width=\""+option.width+"\" height=\""+option.height+"\" scrolling=\"auto\" frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\"></iframe>";
			//设置显示的位置
			this._setPosition();
			document.getElementById("m-ifr").src = file;
			if(isClose) this._createClose();
		}
		else if(type=='file'){	//读取文件内容到页面
			this._request(file + '?' + Math.random(),function(re){
				popMain.innerHTML = re.responseText;
				self._setPosition();
				if(isClose) self._createClose();
			});
		}
		else {					//页面中已有对象innerHTML
			popMain.innerHTML = $app.G(file).innerHTML;
			this._setPosition();
			if(isClose) this._createClose();
		}
		
	},
	
	/**
	 * @name show
	 * @description 删除所有弹出层有关的DOM对象
	 * @author bondli@tencent.com
	 * 
	 */
	removeAll: function(){
		for (var o in this._temp){
			if(o != 'popClose') this._remove(this._temp[o]);
		}
		this._temp.popMask = this._temp.popMain = null;
	},

	/**
	 * @name show
	 * @description 关闭弹出的层
	 * @author bondli@tencent.com
	 * 
	 */
	close: function(){
		this._temp.popMask.style.display = this._temp.popMain.style.display = 'none';
	},
	
	/**
	 * @name _remove
	 * @description 移除DOM对象
	 * @param {Object} obj 需要移除的对象
	 * @author bondli@tencent.com
	 * 
	 */
	_remove: function(obj){
		obj.parentNode.removeChild(obj);
	},
	
	/**
	 * @name _setPosition
	 * @description 设置弹出层的位置
	 * @author bondli@tencent.com
	 * 
	 */
	_setPosition: function(){
		var popMain = this._temp.popMain;
		this._css(popMain,{
			marginLeft:'-' + popMain.offsetWidth/2 + 'px',
			marginTop:'-' + popMain.offsetHeight/2 + 'px'
		});	
	},
	
	/**
	 * @name _extend
	 * @description 参数拓展函数
	 * @param {Object} obj 原来的参数
	 * @param {Object} options 新参数
	 * @return {Object} 拓展后的参数
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
	 * @name _css
	 * @description 为对象设置css属性和值
	 * @param {Object} obj DOM对象
	 * @param {Object} style 样式属性值对象
	 * @author bondli@tencent.com
	 * 
	 */
	_css: function(obj,style){
		for(var o in style){
			obj.style[o] = style[o];
		}
	},
	
	/**
	 * @name createObj
	 * @description 创建对象
	 * @param {String} tag	标签
	 * @param {String} id	DOM对象的ID
	 * @param {Object} style	样式属性值对象
	 * @return {Object} 创建的对象
	 * @author bondli@tencent.com
	 * 
	 */
	_createObj: function(tag,id,style){
		var obj = document.createElement(tag);
		obj.id = id;
		this._css(obj,style);
		return obj;
	},
	
	/**
	 * @name _createMask
	 * @description 创建遮罩层
	 * @author bondli@tencent.com
	 * 
	 */
	_createMask: function(){
		var d = document.documentElement || document.body;
		var scrollHeight = d.scrollHeight > d.offsetHeight ? d.scrollHeight : d.offsetHeight;
	
		this._temp['popMask'] = this._createObj('div','popMask',{
			position:'absolute',
			left:0,
			top:0,
			zIndex:1000,
			width:'100%',
			height:scrollHeight + 'px',
			opacity:'0.7',
			filter:'alpha(opacity=70)',
			background:'#000'
		});
		if (typeof d.style.maxHeight == 'undefined'){
			this._temp['popMaskIframe'] = this._createObj('iframe', 'popMaskIframe', {
				position: 'absolute',
				left: 0,
				top: 0,
				width:'100%',
				height:'100%',
				opacity: 0,
				filter: 'alpha(opacity=0)'			
			});
			this._temp['popMask'].appendChild(this._temp['popMaskIframe']);
		}
		document.body.appendChild(this._temp['popMask']);
	},
	
	/**
	 * @name _createMain
	 * @description 创建主层
	 * @author bondli@tencent.com
	 * 
	 */
	_createMain:function(){
		var d = document.documentElement || document.body;
		this._temp['popMain'] = this._createObj('div','popMain',{
			position:'fixed',
			left:'50%',
			top:'50%',
			zIndex:1002,
			textAlign:'left'
		});
		//ie6下特殊处理
		if (typeof d.style.maxHeight == 'undefined') {
			if(this._temp['popMain'].clientHeight == 'undefined') var objheight = 200;
			else var objheight = this._temp['popMain'].clientHeight;
			this._css(this._temp['popMain'],{
				position:'absolute',
				top:(document.documentElement.clientHeight/2-objheight/2+document.documentElement.scrollTop)+'px'
			});
		};
		document.body.appendChild(this._temp['popMain']);
	},

	/**
	 * @name _createClose
	 * @description创建关闭按钮
	 * @author bondli@tencent.com
	 *
	 */
	_createClose: function(){
		if (!this._temp.popClose){
			
			var closeImg = this._createObj('img', 'popMainClose', {
					position: 'absolute',
					right: '-16px',
					top: '-15px',
					width:'25px',
					height:'29px',
					cursor:'pointer'
			});
			closeImg.src = appConfig.rootPath + 'src/ui/images/x.gif';
			closeImg.onclick = function(){
				$app.modal.removeAll();
			}
			this._temp.popMain.appendChild(closeImg);
			this._temp.popClose = true;
		}
	},

	/**
	 * @name _request
	 * @description ajax请求
	 * @param {String} url 请求的地址
	 * @param {Function} func	回调函数
	 * @author bondli@tencent.com
	 * 
	 */
	_request:function(url,func){
		var thisSelf = this;
		var a = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
		with (a) {
			open("POST", url, true);
			setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
			send();
	        onreadystatechange = function(){
				if (readyState == 4) {
					if (status == 200) func(a);
				}
				else{
					thisSelf._temp['popMain'].innerHTML = "加载中...";
				}
	        }
	    }
	}
};
