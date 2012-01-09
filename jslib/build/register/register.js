$app.register={
    show:function(){
        openRegister(appConfig.tamsid)
        }
    };

document.domain="qq.com";
var g_myBodyInstance=null;
var g_init=null;
var isMinNS4=null;
var isMinNS5=null;
var isMinIE4=null;
var isMinIE5=null;
function getWindowWidth(){
    if(isMinIE4||isMinNS5){
        return(g_myBodyInstance.clientWidth)
        }else{
        if(isMinNS4){
            return(window.innerWidth)
            }
        }
    return(-1)
}
function getWindowHeight(){
    if(isMinIE4||isMinNS5){
        return(g_myBodyInstance.clientHeight)
        }else{
        if(isMinNS4){
            return(window.innerHeight)
            }
        }
    return(-1)
}
function getWidth(a){
    if(isMinIE4||isMinNS5){
        if(a.style.pixelWidth){
            return(a.style.pixelWidth)
            }else{
            return(a.clientWidth)
            }
        }else{
    if(isMinNS4){
        if(a.document.width){
            return(a.document.width)
            }else{
            return(a.clip.right-a.clip.left)
            }
        }
}
return(-1)
}
function getHeight(a){
    if(isMinIE4||isMinNS5){
        if(false&&a.style.pixelHeight){
            return(a.style.pixelHeight)
            }else{
            return(a.clientHeight)
            }
        }else{
    if(isMinNS4){
        if(a.document.height){
            return(a.document.height)
            }else{
            return(a.clip.bottom-a.clip.top)
            }
        }
}
return(-1)
}
function getPageScrollY(){
    if(isMinIE4||isMinNS5){
        return(g_myBodyInstance.scrollTop)
        }else{
        if(isMinNS4){
            return(window.pageYOffset)
            }
        }
    return(-1)
}
function moveLayerTo(b,a,c){
    if(isMinIE4){
        b.style.left=a;
        b.style.top=c
        }else{
        if(isMinNS5){
            b.style.left=a+"px";
            b.style.top=c+"px"
            }else{
            if(isMinNS4){
                b.moveTo(a,c)
                }
            }
    }
}
function openRegister(c){
    register_form();
    mask_wnd=document.getElementById("mask_div");
    if(mask_wnd!=null){
        mask_wnd.style.display="block"
        }
        login_wnd=document.getElementById("register_div");
    if(login_wnd!=null){
        login_wnd.style.visible="hidden";
        login_wnd.style.display="block";
        var a=openRegister.arguments;
        if(a[1]){
            surl=a[1]
            }else{
            surl=window.location
            }
            var b="http://my.act.qq.com/register.html?";
        b+="&actid="+c;
        b+="&s_url="+escape(surl);
        document.getElementById("register_frame").src=b;
        register_onResize(450,300)
        }
    }
function register_form(){
    if(!g_init){
        g_myBodyInstance=document.body;
        isMinNS4=(navigator.appName.indexOf("Netscape")>=0&&parseFloat(navigator.appVersion)>=4)?1:0;
        isMinNS5=(navigator.appName.indexOf("Netscape")>=0&&parseFloat(navigator.appVersion)>=5)?1:0;
        isMinIE4=(document.all)?1:0;
        isMinIE5=(isMinIE4&&navigator.appVersion.indexOf("5.")>=0)?1:0;
        isMacIE=(isMinIE4&&navigator.userAgent.indexOf("Mac")>=0)?1:0;
        isOpera=(navigator.appName.indexOf("Opera")>=0)?1:0;
        if(document.compatMode!="BackCompat"&&!isOpera){
            g_myBodyInstance=document.documentElement
            }
            var a=document.getElementById("mask_div");
        if(!a){
            var e=document.documentElement||document.body;
            var c=e.scrollHeight>e.offsetHeight?e.scrollHeight:e.offsetHeight;
            var f=document.createElement("div");
            f.id="mask_div";
            f.style.display="none";
            f.style.position="absolute";
            f.style.left="0";
            f.style.top="0";
            f.style.width="100%";
            f.style.height=c+"px";
            f.style.zIndex="10";
            f.style.opacity="0.7";
            f.style.filter="alpha(opacity=70)";
            f.style.background="#000";
            document.body.appendChild(f)
            }
            var b=document.getElementById("register_div");
        if(!b){
            var f=document.createElement("div");
            f.id="register_div";
            f.style.display="none";
            f.style.position="absolute";
            f.style.left="40%";
            f.style.top="50%";
            f.style.width="450px";
            f.style.height="300px";
            f.style.padding="0";
            f.style.margin="0";
            f.style.zIndex="99";
            f.innerHTML='<iframe name="register_frame" id="register_frame" frameborder="0" scrolling="auto" width="100%" height="100%" src=""></iframe>';
            document.body.appendChild(f)
            }
            g_init=true
        }
    }
function register_onResize(b,a){
    login_wnd=document.getElementById("register_div");
    if(login_wnd){
        login_wnd.style.width=b+"px";
        login_wnd.style.height=a+"px";
        if(getWindowWidth()!=getWidth(login_wnd)){
            moveLayerTo(login_wnd,(getWindowWidth()-getWidth(login_wnd))/2,getPageScrollY()+(getWindowHeight()-getHeight(login_wnd))/2)
            }
            login_wnd.style.visibility="hidden";
        login_wnd.style.visibility="visible"
        }
    }
function register_onClose(){
    mask_wnd=document.getElementById("mask_div");
    mask_wnd.style.display="none";
    login_wnd=document.getElementById("register_div");
    login_wnd.style.display="none"
    };