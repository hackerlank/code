document.domain="qq.com";
var loader=function(urls,callback){
    var win=window,doc=document,proto="prototype",head=doc.getElementsByTagName("head")[0],sniff=
    /*@cc_on!@*/
    1+/(?:Gecko|AppleWebKit)\/(\S*)/.test(navigator.userAgent);
    var _createNode=function(tag,attrs){
        var attr,node=doc.createElement(tag);
        for(attr in attrs){
            if(attrs.hasOwnProperty(attr)){
                node.setAttribute(attr,attrs[attr])
                }
            }
        return node
    };
    
var load=function(urls,callback){
    if(this==win){
        return new load(urls,callback)
        }
        urls=(typeof urls=="string"?[urls]:urls);
    this.callback=callback||function(){};
    
    this.queue=[];
    var node,i=len=0,that=this;
    var scriptArr=doc.getElementsByTagName("script");
    var sList="";
    for(var t=0;t<scriptArr.length;t++){
        sList+=scriptArr[t].getAttribute("src")+";"
        }
        for(i=0,len=urls.length;i<len;i++){
        this.queue[i]=1;
        var subpath=(appConfig.runMode=="production")?"build":"src";
        var file=appConfig.rootPath+subpath+"/"+urls[i]+".js";
        if(sList.indexOf(file)==-1){
            node=_createNode("script",{
                type:"text/javascript",
                src:file
            });
            head.appendChild(node);
            if(sniff){
                node.onload=function(){
                    try{
                        that.__callback()
                        }catch(e){
                        alert("网络繁忙，请重新刷新!")
                        }
                    }
            }else{
        node.onreadystatechange=function(){
            if(/^loaded|complete$/.test(this.readyState)){
                this.onreadystatechange=null;
                try{
                    that.__callback()
                    }catch(e){
                    alert("网络繁忙，请重新刷新!")
                    }
                }
        }
    }
}else{
    that.__callback()
    }
}
return this
};

load[proto].__callback=function(){
    if(this.queue.pop()&&(this.queue==0)){
        this.callback()
        }
    };

return new load(urls,callback)
};

var loaderStatic=function(c){
    c=(typeof c=="string"?[c]:c);
    var b=(appConfig.runMode=="production")?"build":"src";
    for(i=0,len=c.length;i<len;i++){
        var a=appConfig.rootPath+b+"/"+c[i]+".js";
        document.write('<script type="text/javascript" src="'+a+'"><\/script>')
        }
    };
loaderStatic(appConfig.autoload);
(function(){
    var domReady=!+"\v1"?function(f){
        (function(){
            try{
                document.documentElement.doScroll("left")
                }catch(error){
                setTimeout(arguments.callee,0);
                return
            }
            f()
            })()
        }:function(f){
        document.addEventListener("DOMContentLoaded",function(){
            document.removeEventListener("DOMContentLoaded",arguments.callee,false);
            f()
            },false)
        };
        
    eval(appConfig.namespace+"=domReady")
    })();