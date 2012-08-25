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

function getUrlParam(name)
{
	var requestQueryString = window.location.search.substr(1);
	var requestQueryArray = requestQueryString.split('&');
	var requestQueryParams = new Array();
	for (var i = 0, iMax = requestQueryArray.length; i < iMax; i++) {
		var tempArray = requestQueryArray[i].split('=');
		requestQueryParams[tempArray[0]] = tempArray[1];
	}
	return requestQueryParams[name];
}

String.prototype.substr = function(start, length){
    var str = '';
    if (start > this.length) return str;
    
    length = (length > (this.length-start))?(this.length-start):length;
    for (var i=0;i<length;i++ )
        str += this[start++];
    return str;
}

Array.prototype.remove = function(str){
    for(var i=0, iMax = this.length;i<iMax;i++)
        if(str == this[i]) this.splice(i,1);
}
