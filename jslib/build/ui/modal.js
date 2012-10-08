$app.modal={
    _temp:{},
    show:function(c,e,d,a){
        var g=c?true:false;
        if(!g){
            alert("$app.modal参数错误!");
            return false
            }
            if(a==null){
            a=true
            }
            if(!this._temp.popMask){
            this._createMask()
            }
            if(!this._temp.popMain){
            this._createMain();
            this._temp.popClose=false
            }
            var f=this._temp.popMain;
        var b=this;
        if(e=="iframe"){
            d=this._extend({
                width:"300px",
                height:"300px"
            },d);
            f.innerHTML='<iframe id=\'m-ifr\' src="" width="'+d.width+'" height="'+d.height+'" scrolling="auto" frameborder="0" marginheight="0" marginwidth="0"></iframe>';
            this._setPosition();
            document.getElementById("m-ifr").src=c;
            if(a){
                this._createClose()
                }
            }else{
        if(e=="file"){
            this._request(c+"?"+Math.random(),function(h){
                f.innerHTML=h.responseText;
                b._setPosition();
                if(a){
                    b._createClose()
                    }
                })
        }else{
        f.innerHTML=$app.G(c).innerHTML;
        this._setPosition();
        if(a){
            this._createClose()
            }
        }
}
},
removeAll:function(){
    for(var a in this._temp){
        if(a!="popClose"){
            this._remove(this._temp[a])
            }
        }
    this._temp.popMask=this._temp.popMain=null
},
close:function(){
    this._temp.popMask.style.display=this._temp.popMain.style.display="none"
    },
_remove:function(a){
    a.parentNode.removeChild(a)
    },
_setPosition:function(){
    var a=this._temp.popMain;
    this._css(a,{
        marginLeft:"-"+a.offsetWidth/2+"px",
        marginTop:"-"+a.offsetHeight/2+"px"
        })
    },
_extend:function(b,a){
    for(var c in a){
        b[c]=a[c]
        }
        return b
    },
_css:function(b,a){
    for(var c in a){
        b.style[c]=a[c]
        }
    },
_createObj:function(a,d,b){
    var c=document.createElement(a);
    c.id=d;
    this._css(c,b);
    return c
    },
_createMask:function(){
    var b=document.documentElement||document.body;
    var a=b.scrollHeight>b.offsetHeight?b.scrollHeight:b.offsetHeight;
    this._temp.popMask=this._createObj("div","popMask",{
        position:"absolute",
        left:0,
        top:0,
        zIndex:1000,
        width:"100%",
        height:a+"px",
        opacity:"0.7",
        filter:"alpha(opacity=70)",
        background:"#000"
    });
    if(typeof b.style.maxHeight=="undefined"){
        this._temp.popMaskIframe=this._createObj("iframe","popMaskIframe",{
            position:"absolute",
            left:0,
            top:0,
            width:"100%",
            height:"100%",
            opacity:0,
            filter:"alpha(opacity=0)"
        });
        this._temp.popMask.appendChild(this._temp.popMaskIframe)
        }
        document.body.appendChild(this._temp.popMask)
    },
_createMain:function(){
    var b=document.documentElement||document.body;
    this._temp.popMain=this._createObj("div","popMain",{
        position:"fixed",
        left:"50%",
        top:"50%",
        zIndex:1002,
        textAlign:"left"
    });
    if(typeof b.style.maxHeight=="undefined"){
        if(this._temp.popMain.clientHeight=="undefined"){
            var a=200
            }else{
            var a=this._temp.popMain.clientHeight
            }
            this._css(this._temp.popMain,{
            position:"absolute",
            top:(document.documentElement.clientHeight/2-a/2+document.documentElement.scrollTop)+"px"
            })
        }
        document.body.appendChild(this._temp.popMain)
    },
_createClose:function(){
    if(!this._temp.popClose){
        var a=this._createObj("img","popMainClose",{
            position:"absolute",
            right:"-16px",
            top:"-15px",
            width:"25px",
            height:"29px",
            cursor:"pointer"
        });
        a.src=appConfig.rootPath+"src/ui/images/x.gif";
        a.onclick=function(){
            $app.modal.removeAll()
            };
            
        this._temp.popMain.appendChild(a);
        this._temp.popClose=true
        }
    },
_request:function(url,func){
    var thisSelf=this;
    var a=window.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP"):new XMLHttpRequest();
    with(a){
        open("POST",url,true);
        setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
        send();
        onreadystatechange=function(){
            if(readyState==4){
                if(status==200){
                    func(a)
                    }
                }else{
            thisSelf._temp.popMain.innerHTML="加载中..."
            }
        }
}
}
};