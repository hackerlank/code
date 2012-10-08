/**
 * @fileOverview app jslib UI类弹出右下角消息组件
 * @param {String} id 要显示的对象ID
 * @param {Object} closebtn 关闭按钮的位置和大小，这里是相对位置
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 * 
 */
$app.tips = function(id, closebtn){
	//判断IE版本
	var s = navigator.userAgent.toLowerCase().match(/msie ([\d.]+)/);
	if(s){
		var ie = s[1];
	}else{
		var ie = 0;	
	}
    var obj = document.getElementById(id);
    obj.style.display = "block";
    
	if(ie=='6.0'){
		obj.style.top = (document.documentElement.clientHeight-obj.clientHeight)+'px';
		obj.style.position = "absolute";
	}
	else{
		obj.style.bottom = 0;
		obj.style.position = "fixed";
	}
    obj.style.right = "0";
    obj.style.zIndex = "99";
	
    //closebtn的位置
    if (closebtn == null) {
        closebtn = {
            "width": 15,
            "height": 15,
            "right": 3,
            "top": 3
        };
    }
	
    //创建一个关闭链接
    var a = document.createElement('a');
    a.id = "closebtn";
	if(s){
		a.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		a.style.lineHeight = closebtn.height+'px';
		a.style.overflow = "hidden";
	}
    a.style.display = "block";
    a.style.position = "absolute";
    a.style.cursor = "pointer";
    a.style.right = closebtn.right + 'px';
    a.style.top = closebtn.top + 'px';
    a.style.width = closebtn.width + 'px';
    a.style.height = closebtn.height + 'px';
    a.style.zIndex = "100";
    a.onclick = function(){
        document.getElementById(id).style.display = 'none';
    };
    obj.appendChild(a);
	
	//IE6下才需要特殊设置的
	if(ie=='6.0'){
		window.onscroll = function(){
	        var obj = document.getElementById(id);
	        obj.style.top = (document.documentElement.clientHeight+document.documentElement.scrollTop-obj.clientHeight)+'px';
	    };
	}
};
