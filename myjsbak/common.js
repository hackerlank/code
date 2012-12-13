function SetCookie(name,value,expire)
{
	if(expire) {
		var exp  = new Date();
		exp.setTime(exp.getTime() + expire*1000);
		expires_str = "expires="+exp.toGMTString();
	} else {
		expires_str = '';
	}

	document.cookie = name + "="+ escape (value) + ";path=/;" + expires_str;
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


Array.prototype.remove = function(str){
    for(var i=0, iMax = this.length;i<iMax;i++)
        if(str == this[i]) this.splice(i,1);
}
Array.prototype.inarray = function(str){
	for(var i=0, iMax = this.length; i < iMax; i++)
		if(str == this[i]) return true;
	return false;
}
