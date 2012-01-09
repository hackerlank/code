$app.verifycode={
    _title:"请输入验证码",
    _callback:null,
    _data:null,
    _init:"",
    _myBodyInstance:document.body,
    _cssUrl:appConfig.rootPath+"src/verifycode/skin/qzone.css",
    _cssObj:"",
    _cssRel:"",
    _form:function(l){
        var n=navigator.userAgent.toLowerCase().match(/msie ([\d.]+)/);
        if(n){
            var c=n[1]
            }else{
            var c=0
            }
            if(!this._init){
            var e=(navigator.appName.indexOf("Opera")>=0)?1:0;
            if(document.compatMode!="BackCompat"&&!e){
                this._myBodyInstance=document.documentElement
                }
                var a=document.getElementById("mask_div");
            if(!a){
                var i=(this._myBodyInstance==null)?document.body:this._myBodyInstance;
                var m=i.scrollHeight>i.offsetHeight?i.scrollHeight:i.offsetHeight;
                var b=document.createElement("div");
                b.id="mask_div";
                b.style.display="none";
                b.style.position="absolute";
                b.style.left="0";
                b.style.top="0";
                b.style.width="100%";
                b.style.height=m+"px";
                b.style.zIndex="10";
                b.style.opacity="0.7";
                b.style.filter="alpha(opacity=70)";
                b.style.background="#000";
                document.body.appendChild(b);
                if(c=="6.0"){
                    var f=document.createElement("iframe");
                    f.id="mask_iframe";
                    f.style.position="absolute";
                    f.style.left="0";
                    f.style.top="0";
                    f.style.width="100%";
                    f.style.height=m+"px";
                    f.style.zIndex="-1";
                    f.style.opacity="0";
                    f.style.filter="alpha(opacity=0)";
                    f.style.background="#000";
                    b.appendChild(f)
                    }
                }
            var k=document.getElementById("verify_container");
        if(!k){
            if(!this._cssObj||(this._cssRel!=this._cssUrl)){
                this._cssObj=document.getElementById("verify_css");
                if(!this._cssObj){
                    var h=document.createElement("link");
                    h.id="verify_css";
                    h.rel="stylesheet";
                    h.type="text/css";
                    h.media="screen";
                    h.href=this._cssUrl;
                    document.body.appendChild(h);
                    this._cssObj=h
                    }else{
                    this._cssObj.href=this._cssUrl
                    }
                    this._cssRel=this._cssUrl
                }
                var j=document.createElement("div");
            j.id="verify_container";
            j.style.display="none";
            if(c!="6.0"){
                j.style.position="fixed"
                }
                var g='<div class="app-dialog-top"><a href="javascript:$app.verifycode.close();" class="app-close"></a><h4 class="app-title" title="'+l+'">'+l+'</h4></div><div class="app-dialog-content"><div id="vc_form_div" class="app-login-act" style="height:180px;" ><form id="vc_form" name="vc_form" method="post" enctype="application/x-www-form-urlencoded" autocomplete="off" onsubmit="return $app.verifycode.process();"><ul><li id="verifyinput"><span for="code"><u id="label_vcode">验&nbsp;证&nbsp;码：</u></span><input type="text" tabindex="3" maxlength="4" value="" id="verifycode"  style="ime-mode: disabled;" name="verifycode"/></li><li id="verifytip"><span></span> <u id="label_vcode_tip">输入下图中的字符，不区分大小写</u></li><li id="verifyshow"><span for="pic"></span> <img width="130" height="53" id="imgVerify" src="http://ptlogin2.qq.com/getimage?aid='+appConfig.appid+"&"+Math.random()+'"/><label><a tabindex="6" id="changeimg_link" href="javascript:$app.verifycode.nchangeImg();">看不清，换一张</a></label></li><li><span for="submit"> </span><input name="imageField" type="submit" class="vc_btn button" value="确  定" border="0"></li></ul></form></div></div>';
            j.innerHTML=g;
            document.body.appendChild(j)
            }
            this._init=true
        }
        if(c=="6.0"){
        objheight=180;
        j=document.getElementById("verify_container");
        j.style.top=(document.documentElement.clientHeight/2-objheight/2+document.documentElement.scrollTop)+"px"
        }
    },
show:function(b,c,a){
    this._callback=c;
    this._data=a?a:{};
    
    if(b==""){
        b=this._title
        }
        this._form(b);
    mask_wnd=document.getElementById("mask_div");
    if(mask_wnd!=null){
        mask_wnd.style.display="block"
        }
        login_wnd=document.getElementById("verify_container");
    if(login_wnd!=null){
        login_wnd.style.visible="hidden";
        login_wnd.style.display="block"
        }
        this.nchangeImg()
    },
close:function(){
    mask_wnd=document.getElementById("mask_div");
    mask_wnd.style.display="none";
    login_wnd=document.getElementById("verify_container");
    login_wnd.style.display="none"
    },
nchangeImg:function(c,b){
    c=c||"imgVerify";
    b=b||"verifycode";
    if(appConfig.runMode!="dev"){
        $app.G(c).src="http://captcha.qq.com/getimage?aid="+appConfig.appid+"&"+Math.random()
        }else{
        $app.util.cookie("verifycode","");
        var c=$app.G(c);
        var d=Math.floor(Math.random()*3+1);
        c.src=appConfig.rootPath+"src/verifycode/images/"+d+".jpg";
        var a=["tpyk","mxca","kxzu"];
        $app.util.cookie("verifycode",a[d-1])
        }
        if($app.G(b)){
        $app.G(b).value="";
        $app.G(b).focus()
        }
    },
_validate:function(){
    var a=$app.G("verifycode").value;
    var b=/^[0-9a-zA-Z]{4}$/;
    if(a==""||a.length!=4||!b.test(a)){
        alert("请输入正确的验证码!");
        $app.G("verifycode").focus();
        return false
        }
        return true
    },
process:function(){
    if(!this._validate()){
        return false
        }
        var a=$app.G("verifycode").value;
    this._data.verifycode=a;
    this.close();
    if(this._callback){
        this._callback(this._data)
        }
        return false
    }
};