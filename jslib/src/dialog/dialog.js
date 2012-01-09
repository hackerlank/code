/**
 * @fileOverview app jslib dialog类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.dialog = {
	/**
	 * 回调函数
	 */
	_callback : false,
	/**
	 * 对象是否初始化了
	 */
	_init : '',
	
	/**
	 * body对象
	 */
	_myBodyInstance : document.body,
	
	/**
	 * 皮肤样式文件路径
	 */
	_cssUrl : appConfig.rootPath + 'src/dialog/skin/qzone.css',
	
	/**
	 * 样式对象
	 */
	_cssObj : '',
	
	/**
	 * 样式Rel
	 */
	_cssRel : '',
	
	/**
	 * @name _form
	 * @description 设置表单
	 * @param {String} title 标题
	 * @param {String} content 内容
	 * @param {Number} dwidth 弹出层宽度
	 * @param {Number} dheight 弹出层高度
	 * @author bondli@tencent.com
	 * 
	 */
	_form : function(title,content,dwidth,dheight){
		//判断IE版本
		var nua = navigator.userAgent.toLowerCase().match(/msie ([\d.]+)/);
		if(nua) var ie = nua[1];
		else var ie = 0;
		if(!this._init){
			var isOpera = (navigator.appName.indexOf("Opera") >= 0) ? 1 : 0;
			if (document.compatMode != "BackCompat" && !isOpera )
			{	 
			    this._myBodyInstance = document.documentElement;
			}
			var div_mask = document.getElementById('mask_div');
			if(!div_mask)
			{
				var d = this._myBodyInstance;
				var scrollHeight = d.scrollHeight > d.offsetHeight ? d.scrollHeight : d.offsetHeight;
				var div=document.createElement('div');
				div.id="mask_div";
				div.style.display="none";
				div.style.position="absolute";
				div.style.left="0";
				div.style.top="0";
				div.style.width="100%";
				div.style.height=scrollHeight+'px';
				div.style.zIndex="10";
				div.style.opacity = '0.7';
				div.style.filter = 'alpha(opacity=70)';
				div.style.background = '#000';
				document.body.appendChild(div);
			}
			var div_dialog = document.getElementById('dialog_container');
			if(!div_dialog){
                //加载css
                if (!this._cssObj || (this._cssRel != this._cssUrl)) {
                    this._cssObj = document.getElementById('dialog_css');
                    
                    if (!this._cssObj) {
                        // add css
                        var skin_css = document.createElement('link');
                        skin_css.id = 'dialog_css';
                        skin_css.rel = 'stylesheet';
                        skin_css.type = 'text/css';
                        skin_css.media = 'screen';
                        skin_css.href = this._cssUrl;
                        document.body.appendChild(skin_css);
                        this._cssObj = skin_css;
                    }
                    else {
                        this._cssObj.href = this._cssUrl;
                    }
                    this._cssRel = this._cssUrl;
                }

				var divE = document.createElement('div'); 
				divE.id = "dialog_container";
				divE.style.display = "none";
				if(dwidth!=''){
					divE.style.width = dwidth + 'px';
				}
				if(dheight!=''){
					divE.style.height = dheight + 'px';
				}
				divE.style.marginLeft = '-' + parseInt(dwidth/2) + 'px';
				divE.style.marginTop = '-' + parseInt(dheight/2) + 'px';
				divE.style.left = '50%';
				divE.style.top = '50%';

				//非IE6设置position为fixed
				if(ie!='6.0') {
					divE.style.position = "fixed";
				}
				
                divE.innerHTML = '<div class="app-dialog-top"><a href="javascript:$app.dialog.close();" class="close"></a><h4 class="title" title="'+title+'">'+title+'</h4></div>'+
                '<div class="app-dialog-content">'+ content + '</div>';
				document.body.appendChild(divE);
			}
			
			this._init = true;
		}
		if(ie=='6.0') {	//调整位置
			divE = document.getElementById('dialog_container');
			divE.style.marginTop = 0;
			objheight = parseInt(divE.style.height);
			divE.style.top = (document.documentElement.clientHeight/2-objheight/2+document.documentElement.scrollTop)+'px';
		}
	},
	
	/**
	 * @name show
	 * @description 弹出dialog层
	 * @param {Object} setting 弹出参数
	 * @author bondli@tencent.com
	 * 
	 */
	show : function(setting){
		var callback = setting.callback ? setting.callback : '';
		var title = setting.title ? setting.title : '温馨提示';
		var content = setting.content ? setting.content : '载入中...';
		var width = setting.width ? setting.width : 450;
		var height = setting.height ? setting.height : 180;
		var mask = typeof(setting.mask)!='undefined' ? setting.mask : true;
		this._callback = callback;
		
		//弹出表单
		this._form(title,content,width,height);
		
		if(mask){
			mask_wnd = document.getElementById('mask_div');
			if(mask_wnd != null){
				mask_wnd.style.display = "block";
			}
		}
		
		login_wnd = document.getElementById('dialog_container');
		if (login_wnd != null){
			login_wnd.style.visible = "hidden";	//先隐藏，这样用户就看不到页面的尺寸变化的效果
			login_wnd.style.display = "block";	//设为block， 否则页面不会真正载入
		}
	},

	/**
	 * @name close
	 * @description 关闭弹出框
	 * @author bondli@tencent.com
	 * 
	 */
	close : function(){
		//关闭遮罩层
		mask_wnd = document.getElementById("mask_div");	
		mask_wnd.style.display="none";
		//关闭弹出框
		login_wnd = document.getElementById("dialog_container");	
		login_wnd.style.display="none";
		
		if(this._callback) this._callback();
	}
};
