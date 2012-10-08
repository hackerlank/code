$app.invite=function(b,k,j,d,g){
    var h="http://jump.t.l.qq.com/ping?target="+escape(b)+"&cpid="+appConfig.tamsid+"&type=1&fromqq="+k;
    if(d!=null&&d!=""){
        if(g==true){
            h=d+" "+h
            }else{
            h+=" "+d
            }
        }
    if(window.clipboardData){
    window.clipboardData.clearData();
    window.clipboardData.setData("Text",h)
    }else{
    if(navigator.userAgent.indexOf("Opera")!=-1){
        alert("你的浏览器不支持复制，请用IE或者Firefox来完成复制!");
        return
    }else{
        if(window.netscape){
            try{
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect")
                }catch(l){
                alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'")
                }
                var f=Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);
            if(!f){
                return
            }
            var n=Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
            if(!n){
                return
            }
            n.addDataFlavor("text/unicode");
            var m=new Object();
            var i=new Object();
            var m=Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
            var c=h;
            m.data=c;
            n.setTransferData("text/unicode",m,c.length*2);
            var a=Components.interfaces.nsIClipboard;
            if(!f){
                return false
                }
                f.setData(n,null,a.kGlobalClipboard)
            }else{
            alert("你的浏览器不支持复制，请用IE或者Firefox来完成复制!");
            return
        }
    }
}
if(j==null||j==true){
    alert("地址已经复制到您的剪贴板，您可以发送给您的朋友啦!")
    }
};