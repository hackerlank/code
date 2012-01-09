/**
 * @fileOverview app jslib 验证码类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.verifycode = {
	/**
	 * 标题
	 */
	_title: "请输入验证码",
	
	/**
	 * 回调函数
	 */
	_callback: null,
	
	/**
	 * 需要传进的回调函数数据
	 */
	_data: null,
	
	/**
	 * 验证码对象是否初始化了
	 */
	_init : '',
	
	/**
	 * body对象
	 */
	_myBodyInstance : document.body,
	
	/**
	 * 皮肤样式文件路径
	 */
	_cssUrl : appConfig.rootPath + 'src/verifycode/skin/qzone.css',
	
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
	 * @param {String} title 表单头信息
	 * @author bondli@tencent.com
	 * 
	 */
	_form: function(title){
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
				//页面不规范带来的问题hack
				var d = (this._myBodyInstance == null) ? document.body : this._myBodyInstance;
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
				//创建Iframe
				if(ie=='6.0') {
					var ifrm=document.createElement('iframe');
					ifrm.id="mask_iframe";
					ifrm.style.position="absolute";
					ifrm.style.left="0";
					ifrm.style.top="0";
					ifrm.style.width="100%";
					ifrm.style.height=scrollHeight+'px';
					ifrm.style.zIndex="-1";
					ifrm.style.opacity = '0';
					ifrm.style.filter = 'alpha(opacity=0)';
					ifrm.style.background = '#000';
					div.appendChild(ifrm);
				}
			}
			var div_verify = document.getElementById('verify_container');
			if(!div_verify){
                //加载css
                if (!this._cssObj || (this._cssRel != this._cssUrl)) {
                    this._cssObj = document.getElementById('verify_css');
                    
                    if (!this._cssObj) {
                        // add css
                        var skin_css = document.createElement('link');
                        skin_css.id = 'verify_css';
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
				divE.id = "verify_container";
				divE.style.display = "none";
				//非IE6设置position为fixed
				if(ie!='6.0') {
					divE.style.position = "fixed";
				}
				
                var vc_HTML = '<div class="app-dialog-top"><a href="javascript:$app.verifycode.close();" class="app-close"></a><h4 class="app-title" title="'+title+'">'+title+'</h4></div>'+
                '<div class="app-dialog-content">'+
                '<div id="vc_form_div" class="app-login-act" style="height:180px;" >' +
				'<form id="vc_form" name="vc_form" method="post" enctype="application/x-www-form-urlencoded" autocomplete="off" onsubmit="return $app.verifycode.process();">' +
				'<ul>' +
				'<li id="verifyinput"><span for="code"><u id="label_vcode">验&nbsp;证&nbsp;码：</u></span>' +
				'<input type="text" tabindex="3" maxlength="4" value="" id="verifycode"  style="ime-mode: disabled;" name="verifycode"/>' +
				'</li>' +
				'<li id="verifytip"><span></span> <u id="label_vcode_tip">输入下图中的字符，不区分大小写</u></li>' +
				'<li id="verifyshow"><span for="pic"></span> <img width="130" height="53" id="imgVerify" src="http://ptlogin2.qq.com/getimage?aid='+appConfig.appid+'&' + Math.random() + '"/>' +
				'<label><a tabindex="6" id="changeimg_link" href="javascript:$app.verifycode.nchangeImg();">看不清，换一张</a></label>' +
				'</li>' +
				'<li><span for="submit"> </span>' +
				'<input name="imageField" type="submit" class="vc_btn button" value="确  定" border="0">' +
				'</li>' +
				'</ul>' +
				'</form>' +
				'</div>' +
				'</div>';
                divE.innerHTML = vc_HTML;
				document.body.appendChild(divE);
			}
			
			this._init = true;
		}
		if(ie=='6.0') {	//调整位置
			objheight = 180;
			divE = document.getElementById('verify_container');
			divE.style.top = (document.documentElement.clientHeight/2-objheight/2+document.documentElement.scrollTop)+'px';
		}
	},
	
	/**
	 * @name show
	 * @description 弹出浮沉方式显示验证码
	 * @param {String} title 表单头显示信息
	 * @param {Function} callback 回调函数
	 * @param {Object} data 传入回调函数的数据
	 * @author bondli@tencent.com
	 * 
	 */
	show: function(title, callback, data){
		this._callback = callback;
		this._data = data?data:{};
		//弹出表单
		if(title == '') title = this._title;
		this._form(title);
		mask_wnd = document.getElementById('mask_div');
		if(mask_wnd != null){
			mask_wnd.style.display = "block";
		}
		
		login_wnd = document.getElementById('verify_container');
		if (login_wnd != null){
			login_wnd.style.visible = "hidden";	//先隐藏，这样用户就看不到页面的尺寸变化的效果
			login_wnd.style.display = "block";	//设为block， 否则页面不会真正载入
		}
		//刷新验证码
		this.nchangeImg();
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
		//关闭登录框
		login_wnd = document.getElementById("verify_container");	
		login_wnd.style.display="none";
	},

	/**
	 * @name nchangeImg
	 * @description 刷新验证码
	 * @param {String} img 图片ID
	 * @param {String} inputBox 输入框的ID
	 * @author bondli@tencent.com
	 * 
	 */
	nchangeImg: function(img, inputBox) {
		img = img || "imgVerify";
		inputBox = inputBox || "verifycode";
		if(appConfig.runMode != 'dev'){
        	$app.G(img).src = "http://captcha.qq.com/getimage?aid=" + appConfig.appid + "&" + Math.random();
		}
		else{	//外包模式
        	$app.util.cookie('verifycode','');
			var img = $app.G(img);
			var random = Math.floor(Math.random()*3+1);
			img.src = appConfig.rootPath + "src/verifycode/images/" + random + ".jpg";
			//把验证码写入cookie中
			var arr = ['tpyk','mxca','kxzu'];
			$app.util.cookie('verifycode',arr[random-1]);
		}
		if($app.G(inputBox)) {
			$app.G(inputBox).value = '';
			$app.G(inputBox).focus();
		}
	},

	/**
	 * @name _validate
	 * @description 验证码有效性验证
	 * @return {Boolean} 验证码是否有效
	 * @author bondli@tencent.com
	 * 
	 */
	_validate: function(){
		var verifycode = $app.G("verifycode").value;
		var reg = /^[0-9a-zA-Z]{4}$/;
		if (verifycode == "" || verifycode.length != 4 || !reg.test(verifycode)) {
			alert("请输入正确的验证码!");
			$app.G("verifycode").focus();
			return false;
		}
		return true;
	},

	/**
	 * @name process
	 * @description 提交处理
	 * @author bondli@tencent.com
	 * 
	 */
	process: function(){
		if (!this._validate()) {
			return false;
		}
		var verifycode = $app.G("verifycode").value;

		this._data.verifycode = verifycode;
		this.close();
		if(this._callback){
			this._callback(this._data);
		}
		return false;
	}
};
