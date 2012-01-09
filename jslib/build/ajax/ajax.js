$app.ajax={
    sync:function(a,b,c){
        b=(b==null)?"get":b;
        c=(c==null)?null:c;
        return this.init({
            type:b,
            url:a,
            data:c,
            dataType:"text",
            success:function(){},
            async:false
        }).responseText
        },
    get:function(a,c,b){
        b=(b==null)?"text":b;
        return this.init({
            type:"GET",
            url:a,
            success:c,
            dataType:b
        })
        },
    getScript:function(a,b){
        return this.get(a,b,"script")
        },
    getJSON:function(a,b){
        return this.get(a,b,"json")
        },
    getJSONP:function(a,b){
        return this.get(a,b,"jsonp")
        },
    post:function(a,c,d,b){
        return this.init({
            type:"POST",
            url:a,
            data:c,
            success:d,
            dataType:b
        })
        },
    ajaxSettings:{
        url:location.href,
        global:true,
        type:"GET",
        timeout:0,
        contentType:"application/x-www-form-urlencoded",
        processData:true,
        async:true,
        data:null,
        username:null,
        password:null,
        accepts:{
            xml:"application/xml, text/xml",
            html:"text/html",
            script:"text/javascript, application/javascript",
            json:"application/json, text/javascript",
            text:"text/plain",
            _default:"*/*"
        }
    },
lastModified:{},
extend:function(b,a){
    for(var c in a){
        b[c]=a[c]
        }
        return b
    },
handleError:function(b,d,a,c){
    if(b.error){
        b.error(d,a,c)
        }
    },
active:0,
httpSuccess:function(b){
    try{
        return !b.status&&location.protocol=="file:"||(b.status>=200&&b.status<300)||b.status==304||b.status==1223||this.browser.safari&&b.status==undefined
        }catch(a){}
    return false
    },
httpNotModified:function(c,a){
    try{
        var d=c.getResponseHeader("Last-Modified");
        return c.status==304||d==this.lastModified[a]||this.browser.safari&&c.status==undefined
        }catch(b){}
    return false
    },
httpData:function(xhr,type,s){
    var ct=xhr.getResponseHeader("content-type"),xml=type=="xml"||!type&&ct&&ct.indexOf("xml")>=0,data=xml?xhr.responseXML:xhr.responseText;
    if(xml&&data.documentElement.tagName=="parsererror"){
        throw"parsererror"
        }
        if(s&&s.dataFilter){
        data=s.dataFilter(data,type)
        }
        if(type=="json"){
        data=eval("("+data+")")
        }
        return data
    },
param:function(b){
    var c=[];
    function d(a,e){
        c[c.length]=encodeURIComponent(a)+"="+encodeURIComponent(e)
        }
        if(typeof b=="object"){
        for(key in b){
            d(key,b[key])
            }
        }
        return c.join("&").replace(/%20/g,"+")
},
browser:{
    version:(navigator.userAgent.toLowerCase().match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/)||[0,"0"])[1],
    safari:/webkit/.test(navigator.userAgent.toLowerCase()),
    opera:/opera/.test(navigator.userAgent.toLowerCase()),
    msie:/msie/.test(navigator.userAgent.toLowerCase())&&!/opera/.test(navigator.userAgent.toLowerCase()),
    mozilla:/mozilla/.test(navigator.userAgent.toLowerCase())&&!/(compatible|webkit)/.test(navigator.userAgent.toLowerCase())
    },
init:function(j){
    var l=this;
    j=this.extend(this.ajaxSettings,j);
    var v,b=/=\?(&|$)/g,p,u,c=j.type.toUpperCase();
    if(j.data&&j.processData&&typeof j.data!="string"){
        j.data=this.param(j.data)
        }
        if(j.dataType=="jsonp"){
        if(c=="GET"){
            if(!j.url.match(b)){
                j.url+=(j.url.match(/\?/)?"&":"?")+(j.jsonp||"callback")+"=?"
                }
            }else{
        if(!j.data||!j.data.match(b)){
            j.data=(j.data?j.data+"&":"")+(j.jsonp||"callback")+"=?"
            }
        }
    j.dataType="json"
}
if(j.dataType=="json"&&(j.data&&j.data.match(b)||j.url.match(b))){
    v="jsonp"+new Date().getTime();
    if(j.data){
        j.data=(j.data+"").replace(b,"="+v+"$1")
        }
        j.url=j.url.replace(b,"="+v+"$1");
    j.dataType="script";
    window[v]=function(s){
        u=s;
        f();
        i();
        window[v]=undefined;
        try{
            delete window[v]
        }catch(w){}
        if(d){
            r.parentNode.removeChild(r)
            }
        }
}
if(j.dataType=="script"&&j.cache==null){
    j.cache=false
    }
    if(j.cache===false&&c=="GET"){
    var a=new Date().getTime();
    var t=j.url.replace(/(\?|&)_=.*?(&|$)/,"$1_="+a+"$2");
    j.url=t+((t==j.url)?(j.url.match(/\?/)?"&":"?")+"_="+a:"")
    }
    if(j.data&&c=="GET"){
    j.url+=(j.url.match(/\?/)?"&":"?")+j.data;
    j.data=null
    }
    var o=/^(\w+:)?\/\/([^\/?#]+)/.exec(j.url);
if(j.dataType=="script"&&c=="GET"&&o){
    var d=document.getElementsByTagName("head")[0];
    var r=document.createElement("script");
    r.src=j.url;
    if(j.scriptCharset){
        r.charset=j.scriptCharset
        }
        if(!v){
        var m=false;
        r.onload=r.onreadystatechange=function(){
            if(!m&&(!this.readyState||this.readyState=="loaded"||this.readyState=="complete")){
                m=true;
                f();
                i();
                d.removeChild(r)
                }
            }
    }
d.appendChild(r);
return undefined
}
var h=false;
var g=window.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP"):new XMLHttpRequest();
if(j.username){
    g.open(c,j.url,j.async,j.username,j.password)
    }else{
    g.open(c,j.url,j.async)
    }
    try{
    if(j.data){
        g.setRequestHeader("Content-Type",j.contentType)
        }
        if(j.ifModified){
        g.setRequestHeader("If-Modified-Since",this.lastModified[j.url]||"Thu, 01 Jan 1970 00:00:00 GMT")
        }
        g.setRequestHeader("X-Requested-With","XMLHttpRequest");
    g.setRequestHeader("Accept",j.dataType&&j.accepts[j.dataType]?j.accepts[j.dataType]+", */*":j.accepts._default)
    }catch(q){}
if(j.beforeSend&&j.beforeSend(g,j)===false){
    j.global&&this.active--;
    g.abort();
    return false
    }
    var k=function(s){
    if(!h&&g&&(g.readyState==4||s=="timeout")){
        h=true;
        if(n){
            clearInterval(n);
            n=null
            }
            p=s=="timeout"?"timeout":!l.httpSuccess?"error":j.ifModified&&l.httpNotModified(g,j.url)?"notmodified":"success";
        if(p=="success"){
            try{
                u=l.httpData(g,j.dataType,j)
                }catch(x){
                p="parsererror"
                }
            }
        if(p=="success"){
        var w;
        try{
            w=g.getResponseHeader("Last-Modified")
            }catch(x){}
        if(j.ifModified&&w){
            l.lastModified[j.url]=w
            }
            if(!v){
            f()
            }
        }else{
    l.handleError(j,g,p)
    }
    i();
if(j.async){
    g=null
    }
}
};

if(j.async){
    var n=setInterval(k,13);
    if(j.timeout>0){
        setTimeout(function(){
            if(g){
                g.abort();
                if(!h){
                    k("timeout")
                    }
                }
        },j.timeout)
}
}
try{
    g.send(j.data)
    }catch(q){
    l.handleError(j,g,null,q)
    }
    if(!j.async){
    k()
    }
    function f(){
    if(j.success){
        j.success(u,p)
        }
    }
function i(){
    if(j.complete){
        j.complete(g,p)
        }
    }
return g
}
};