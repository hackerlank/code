/**
 * @fileOverview app jslib 注册类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.register = {
	/**
	 * @name show
	 * @description 显示注册框
	 * @author bondli@tencent.com
	 * 
	 */
	show: function() {
		openRegister(appConfig.tamsid);
	}
};

document.domain="qq.com";
var g_myBodyInstance = null;
var g_init   = null;
var isMinNS4 = null;
var isMinNS5 = null;
var isMinIE4 = null;
var isMinIE5 = null;
/**
 * @name getWindowWidth
 * @description 获得当前窗口宽度
 * @return {Number} 窗口宽度
 * @field
 * 
 */
function getWindowWidth()
{
	if (isMinIE4||isMinNS5)
		return (g_myBodyInstance.clientWidth);
	else if (isMinNS4)
		return (window.innerWidth);
	return (-1);
}
/**
 * @name getWindowHeight
 * @description 获得当前窗口高度
 * @return {Number} 窗口高度
 * @field
 * 
 */
function getWindowHeight()
{
	if (isMinIE4||isMinNS5)
		return (g_myBodyInstance.clientHeight);
	else if (isMinNS4)
		return(window.innerHeight);
	
	return(-1);
}
/**
 * @name getWidth
 * @description 获得对象的宽度
 * @return {Number} 对象宽度
 * @field
 * 
 */
function getWidth(layer)
{
	if (isMinIE4||isMinNS5)
	{
		if (layer.style.pixelWidth)
			return (layer.style.pixelWidth);
		else
			return (layer.clientWidth);
	}
	else if (isMinNS4)
	{
		if (layer.document.width)
			return (layer.document.width);
		else
			return (layer.clip.right - layer.clip.left);
	}
	
	return (-1);
}
/**
 * @name getHeight
 * @description 获得对象的高度
 * @return {Number} 对象高度
 * @field
 * 
 */
function getHeight(layer)
{
	if (isMinIE4||isMinNS5)
	{
		if (false && layer.style.pixelHeight)
			return (layer.style.pixelHeight);
		else
			return (layer.clientHeight);
	}
	else if (isMinNS4)
	{
		if (layer.document.height)
			return (layer.document.height);
		else
			return (layer.clip.bottom - layer.clip.top);
	}
	
	return(-1);
}
/**
 * @name getPageScrollY
 * @description 获得被浏览器滚动的高度
 * @return {Number} 被滚动的高度
 * @field
 * 
 */
function getPageScrollY()
{
	if (isMinIE4||isMinNS5)
		return (g_myBodyInstance.scrollTop);
	else if (isMinNS4)
		return (window.pageYOffset);
	
	return (-1);
}
/**
 * @name moveLayerTo
 * @description 把对象移动到指定的位置
 * @field
 * 
 */
function moveLayerTo(layer, x, y)
{
	if (isMinIE4)
	{
		layer.style.left = x;
		layer.style.top  = y;
	}
	else if (isMinNS5)
	{
		layer.style.left = x+'px';
		layer.style.top  = y+'px';
	}
	else if (isMinNS4)
    	layer.moveTo(x, y);
}

/**
 * @name openLogin
 * @description 弹出注册框
 * @param {Number} actid 活动ID
 * @field
 * 
 */
function openRegister(actid)
{
	register_form();
	mask_wnd = document.getElementById('mask_div');
	if(mask_wnd != null){
		mask_wnd.style.display = "block";
	}
	
	login_wnd = document.getElementById("register_div");
	if (login_wnd != null){
		login_wnd.style.visible = "hidden";	//先隐藏，这样用户就看不到页面的尺寸变化的效果
		login_wnd.style.display = "block";	//设为block， 否则页面不会真正载入
		
		var argv = openRegister.arguments;
		if ( argv[1] )
		{
			surl = argv[1];
		}
		else
		{
			surl = window.location;
		}		
		
		var url = "http://my.act.qq.com/register.html" + "?";
		url += "&actid="  + actid;
	  	url += "&s_url="  + escape(surl);
		
		document.getElementById("register_frame").src = url;
		
		register_onResize(450,300);
	}
}

/**
 * @name ptlogin2_form
 * @description 构建遮罩层和登录层
 * @field
 * 
 */
function register_form()
{
	if ( !g_init )
	{
		g_myBodyInstance = document.body;
		isMinNS4 = (navigator.appName.indexOf("Netscape") >= 0 && parseFloat(navigator.appVersion) >= 4) ? 1 : 0;
		isMinNS5 = (navigator.appName.indexOf("Netscape") >= 0 && parseFloat(navigator.appVersion) >= 5) ? 1 : 0;
		isMinIE4 = (document.all) ? 1 : 0;
		isMinIE5 = (isMinIE4 && navigator.appVersion.indexOf("5.") >= 0) ? 1 : 0;
		isMacIE = (isMinIE4 && navigator.userAgent.indexOf("Mac") >= 0) ? 1 : 0;
		isOpera = (navigator.appName.indexOf("Opera") >= 0) ? 1 : 0;	
		if (document.compatMode != "BackCompat" && !isOpera )
		{	 
		    g_myBodyInstance = document.documentElement;
		}
		
		var div_mask=document.getElementById('mask_div');
		if(!div_mask)
		{
			var d = document.documentElement || document.body;
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
		
		var div_=document.getElementById('register_div');
		if(!div_)
		{
			var div=document.createElement('div'); 
			div.id="register_div";
			div.style.display="none";
			div.style.position="absolute";
			div.style.left="40%";
			div.style.top="50%";
			div.style.width="450px";
			div.style.height="300px";
			div.style.padding="0";
			div.style.margin="0";
			div.style.zIndex="99";
			
			div.innerHTML="<iframe name=\"register_frame\" id=\"register_frame\" frameborder=\"0\" scrolling=\"auto\" width=\"100%\" height=\"100%\" src=\"\"></iframe>";
			document.body.appendChild(div);
		}
		g_init = true;
	}
}

/**
 * @name ptlogin2_onResize
 * @description 调整登录框位置
 * @field
 * 
 */
function register_onResize(width, height)
{	
	login_wnd = document.getElementById("register_div");
	if (login_wnd)
	{
		login_wnd.style.width = width + "px";
		login_wnd.style.height = height + "px";

		//确定位置
		if ( getWindowWidth() != getWidth(login_wnd) )
		{
			moveLayerTo(login_wnd,(getWindowWidth()-getWidth(login_wnd))/2,getPageScrollY()+(getWindowHeight()-getHeight(login_wnd))/2);
		}
		
		login_wnd.style.visibility = "hidden";
		login_wnd.style.visibility = "visible";
	}
}
/**
 * @name ptlogin2_onClose
 * @description 执行关闭登录框
 * @field
 * 
 */
function register_onClose()
{
	//关闭遮罩层
	mask_wnd = document.getElementById("mask_div");	
	mask_wnd.style.display="none";
	//关闭注册框
	login_wnd = document.getElementById("register_div");	
	login_wnd.style.display="none";
}