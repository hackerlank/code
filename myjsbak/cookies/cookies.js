function SetCookie(name,value)
{
    var exp  = new Date();    //new Date("December 31, 9998");
    exp.setTime(exp.getTime() + 1*60*60*1000);
    document.cookie = name + "="+ escape (value) + "; path=/;";
}
function getCookie(name)//ȡcookies����        
{
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
     if(arr != null) return unescape(arr[2]); return null;

}
function delCookie(name)//ɾ��cookie
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}