function SetCookie(name,value,expire)
{
	if (0 == expire) {
		expireStr = '';
	} else { 
		var exp  = new Date(); 
		exp.setTime(exp.getTime() + expire);
		expireStr = "expires="+exp.toGMTString;
	}
    document.cookie = name + "="+ escape (value) + "; path=/;"+expireStr;
}
function getCookie(name)
{
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
     if(arr != null) return unescape(arr[2]); return null;
}
function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}