$app.util={
    getURLParam:function(a){
        var i="";
        var e=window.location.href;
        var j=false;
        var f=a+"=";
        var c=f.length;
        if(e.indexOf("?")>-1){
            var g=e.substr(e.indexOf("?")+1);
            var d=g.split("&");
            for(var b=0;b<d.length;b++){
                if(d[b].substr(0,c)==f){
                    var h=d[b].split("=");
                    i=h[1];
                    j=true;
                    break
                }
            }
            }
        if(j==false){
    return null
    }
    return i
},
trim:function(a){
    return a.replace(/^\s*(.*?)\s*$/,"$1")
    },
cookie:function(b,j,m){
    if(typeof j!="undefined"){
        m=m||{};
        
        if(j===null){
            j="";
            m.expires=-1
            }
            var e="";
        if(m.expires&&(typeof m.expires=="number"||m.expires.toUTCString)){
            var f;
            if(typeof m.expires=="number"){
                f=new Date();
                f.setTime(f.getTime()+(m.expires*1000))
                }else{
                f=m.expires
                }
                e="; expires="+f.toUTCString()
            }
            var l=m.path?"; path="+m.path:"";
        var g=m.domain?"; domain="+m.domain:"";
        var a=m.secure?"; secure":"";
        document.cookie=[b,"=",encodeURIComponent(j),e,l,g,a].join("")
        }else{
        var d=null;
        if(document.cookie&&document.cookie!=""){
            var k=document.cookie.split(";");
            for(var h=0;h<k.length;h++){
                var c=$app.util.trim(k[h]);
                if(c.substring(0,b.length+1)==(b+"=")){
                    d=decodeURIComponent(c.substring(b.length+1));
                    break
                }
            }
            }
        return d
}
},
copyToClipboard:function(b){
    if(window.clipboardData){
        window.clipboardData.setData("Text",b);
        return""
        }else{
        if(navigator.userAgent.indexOf("Opera")!=-1){
            alert("你的浏览器不支持复制，请用IE或者Firefox来完成复制!");
            return""
            }else{
            if(window.netscape){
                try{
                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect")
                    }catch(g){
                    return"您的firefox安全设置限制您进行剪贴板操作，请打开'about:config'将signed.applets.codebase_principal_support'设置为true'之后重试"
                    }
                    var d=Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);
                if(!d){
                    return""
                    }
                    var c=Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
                if(!c){
                    return""
                    }
                    c.addDataFlavor("text/unicode");
                var h=new Object();
                var a=new Object();
                var h=Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
                var i=b;
                h.data=i;
                c.setTransferData("text/unicode",h,i.length*2);
                var f=Components.interfaces.nsIClipboard;
                if(!d){
                    return""
                    }
                    d.setData(c,null,f.kGlobalClipboard);
                return""
                }else{
                alert("你的浏览器不支持复制，请用IE或者Firefox来完成复制!")
                }
            }
    }
},
favorite:function(b,a){
    if(typeof b=="undefined"){
        b=window.location.href
        }
        if(typeof a=="undefined"){
        a=window.title
        }
        try{
        window.external.addFavorite(b,a)
        }catch(c){
        try{
            window.sidebar.addPanel(a,b,"")
            }catch(c){
            alert("加入收藏失败，请使用Ctrl+D进行添加")
            }
        }
},
log:function(a){
    if(jQuery.browser.msie){
        return
    }else{
        console.log(a)
        }
    }
};