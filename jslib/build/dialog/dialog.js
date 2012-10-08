$app.dialog={
    _callback:false,
    _init:"",
    _myBodyInstance:document.body,
    _cssUrl:appConfig.rootPath+"src/dialog/skin/qzone.css",
    _cssObj:"",
    _cssRel:"",
    _form:function(l,h,m,e){
        var o=navigator.userAgent.toLowerCase().match(/msie ([\d.]+)/);
        if(o){
            var c=o[1]
            }else{
            var c=0
            }
            if(!this._init){
            var f=(navigator.appName.indexOf("Opera")>=0)?1:0;
            if(document.compatMode!="BackCompat"&&!f){
                this._myBodyInstance=document.documentElement
                }
                var a=document.getElementById("mask_div");
            if(!a){
                var j=this._myBodyInstance;
                var n=j.scrollHeight>j.offsetHeight?j.scrollHeight:j.offsetHeight;
                var b=document.createElement("div");
                b.id="mask_div";
                b.style.display="none";
                b.style.position="absolute";
                b.style.left="0";
                b.style.top="0";
                b.style.width="100%";
                b.style.height=n+"px";
                b.style.zIndex="10";
                b.style.opacity="0.7";
                b.style.filter="alpha(opacity=70)";
                b.style.background="#000";
                document.body.appendChild(b)
                }
                var g=document.getElementById("dialog_container");
            if(!g){
                if(!this._cssObj||(this._cssRel!=this._cssUrl)){
                    this._cssObj=document.getElementById("dialog_css");
                    if(!this._cssObj){
                        var i=document.createElement("link");
                        i.id="dialog_css";
                        i.rel="stylesheet";
                        i.type="text/css";
                        i.media="screen";
                        i.href=this._cssUrl;
                        document.body.appendChild(i);
                        this._cssObj=i
                        }else{
                        this._cssObj.href=this._cssUrl
                        }
                        this._cssRel=this._cssUrl
                    }
                    var k=document.createElement("div");
                k.id="dialog_container";
                k.style.display="none";
                if(m!=""){
                    k.style.width=m+"px"
                    }
                    if(e!=""){
                    k.style.height=e+"px"
                    }
                    k.style.marginLeft="-"+parseInt(m/2)+"px";
                k.style.marginTop="-"+parseInt(e/2)+"px";
                k.style.left="50%";
                k.style.top="50%";
                if(c!="6.0"){
                    k.style.position="fixed"
                    }
                    k.innerHTML='<div class="app-dialog-top"><a href="javascript:$app.dialog.close();" class="close"></a><h4 class="title" title="'+l+'">'+l+'</h4></div><div class="app-dialog-content">'+h+"</div>";
                document.body.appendChild(k)
                }
                this._init=true
            }
            if(c=="6.0"){
            k=document.getElementById("dialog_container");
            k.style.marginTop=0;
            objheight=parseInt(k.style.height);
            k.style.top=(document.documentElement.clientHeight/2-objheight/2+document.documentElement.scrollTop)+"px"
            }
        },
show:function(d){
    var g=d.callback?d.callback:"";
    var f=d.title?d.title:"温馨提示";
    var e=d.content?d.content:"载入中...";
    var c=d.width?d.width:450;
    var a=d.height?d.height:180;
    var b=typeof(d.mask)!="undefined"?d.mask:true;
    this._callback=g;
    this._form(f,e,c,a);
    if(b){
        mask_wnd=document.getElementById("mask_div");
        if(mask_wnd!=null){
            mask_wnd.style.display="block"
            }
        }
    login_wnd=document.getElementById("dialog_container");
    if(login_wnd!=null){
    login_wnd.style.visible="hidden";
    login_wnd.style.display="block"
    }
},
close:function(){
    mask_wnd=document.getElementById("mask_div");
    mask_wnd.style.display="none";
    login_wnd=document.getElementById("dialog_container");
    login_wnd.style.display="none";
    if(this._callback){
        this._callback()
        }
    }
};