var mo=(function(c,e,a){
    var h=e.each,b=function(l){
        var m=(l+"");
        return m.substring(m.length-6)
        },f={
        ua:(function(){
            for(var l in a){
                if(a[l]!=0){
                    return l+a[l]
                    }
                }
            })(),
    domain:(function(n){
    try{
        var l=n.match(/http:\/\/([^\/]*)\/|$/i);
        return l[1]
        }catch(o){
        return""
        }
    })(location.href),
QosS:b((new Date()).getTime())
},g=e.__images=[],k=function(m){
    var l=new Image(1,1);
    l.src=m;
    g.push(l);
    return this
    },d=function(){
    return true
    },j=function(){
    if(d()){
        k(c+"?"+e.serializeQuery(f))
        }
    };

var i=(function(){
    var l={};
    
    if(document.addEventListener){
        l.on=function(o,n,m){
            o.addEventListener(n,m,false);
            return m
            };
            
        l.on2=l.on;
        l.un=function(o,n,m){
            o.removeEventListener(n,m,false)
            };
            
        l.stopPropagation=function(m){
            m.stopPropagation()
            };
            
        l.preventDefault=function(m){
            m.preventDefault()
            };
            
        l.getTarget=function(m){
            return m.target
            }
        }else{
    l.on=function(o,n,m){
        o.attachEvent("on"+n,m)
        };
        
    l.on2=function(o,n,m){
        var p=function(){
            m.call(o,window.event)
            };
            
        o.attachEvent("on"+n,p);
        return p
        };
        
    l.un=function(o,n,m){
        o.detachEvent("on"+n,m)
        };
        
    l.stopPropagation=function(m){
        m.cancelBubble=true
        };
        
    l.preventDefault=function(m){
        m.returnValue=false
        };
        
    l.getTarget=function(m){
        return m.srcElement
        }
    }
l.stop=function(m){
    l.stopPropagation(m);
    l.preventDefault(m)
    };
(function(){
    var o=[];
    var n=false;
    l.ready=function(q){
        o.push(q)
        };
        
    var m=function(){
        if(!n){
            n=true;
            l.ready=function(q){
                q()
                };
                
            h(o,function(q){
                q()
                })
            }
        };
    
if(a.ie){
    var p=setInterval(function(){
        try{
            document.doScroll();
            clearInterval(p);
            p=null;
            m()
            }catch(q){}
    },64);
document.attachEvent("onreadystatechange",function(){
    if(document.readyState==="complete"){
        document.detachEvent("onreadystatechange",arguments.callee);
        m()
        }
    })
}else{
    l.on(document,"DOMContentLoaded",m)
    }
    l.on(window,"load",m)
})();
return l
})();
i.ready(function(){
    mo.append("first_screen")
    });
i.on(window,"load",function(){
    mo.append("full_screen");
    j()
    });
return{
    append:function(l){
        f[l]=b((new Date()).getTime())
        }
    }
})("http://dp3.qq.com/play/",(function(){
    var c=function(h,g,f){
        if(typeof h.length=="number"){
            for(var e=0,d=h.length;e<d;e++){
                g.call(f,h[e],e)
                }
            }else{
        if(typeof h=="number"){
            for(var e=0;e<h;e++){
                g.call(f,e,e)
                }
            }else{
        for(var e in h){
            if(h.hasOwnProperty(e)){
                g.call(f,h[e],e)
                }
            }
        }
    }
},b=function(h,f,e,d){
    var g=d?encodeURIComponent:function(i){
        return i
        };
        
    return function(j){
        var i=[];
        c(j,function(m,l){
            if(l!=null&&m!=undefined){
                i.push(g(l)+h+g(m))
                }
            });
    return i.join(f)+(e?f:"")
    }
},a=function(h,f,d,e){
    var g=e?decodeURIComponent:function(i){
        return i
        };
        
    return function(j){
        var i={};
        
        if(d){
            j=j.replace(new RegExp(f+"$"),"")
            }
            c(j.split(f),function(l){
            var k=l.split(h);
            i[g(k[0])]=g(k[1])
            });
        return i
        }
    };

return{
    slice:Array.slice||(function(){
        var d=Array.prototype.slice;
        return function(e){
            return d.apply(e,d.call(arguments,1))
            }
        })(),
generateId:(function(){
    var d=1;
    return function(){
        return"auto_gen_"+d++
        }
    })(),
each:c,
map:function(f,e){
    var d=[];
    c(f,function(h,g){
        d.push(e(h,g))
        });
    return d
    },
filter:function(f,e){
    var d=[];
    c(f,function(h,g){
        if(e(h,g)===true){
            d.push(h)
            }
        });
return d
},
indexOf:function(d,f){
    if(d.indexOf){
        return d.indexOf(f)
        }
        for(var e=0;e<d.length;e++){
        if(d[e]===f){
            return e
            }
        }
    return -1
},
mix:function(g){
    if(!g){
        g={}
    }
    for(var e=1;e<arguments.length;e++){
    var f=arguments[e];
    if(f){
        for(var d in f){
            g[d]=f[d]
            }
        }
        }
return g
},
serializeDictionary:b,
deserializeString:a,
serializeStyles:b(":",";",true,false),
serializeAttrs:(function(){
    var d=b("="," ",true,false);
    return function(e){
        c(e,function(g,f){
            e[f]='"'+g+'"'
            });
        return d(e)
        }
    })(),
serializeQuery:b("=","&",false,true),
buffer:function(e,d){
    var f;
    return function(){
        if(f){
            clearTimeout(f)
            }
            var g=arguments;
        f=setTimeout(function(){
            e.apply(window,g)
            },d||100)
        }
    },
format:function(f,e,d){
    return f.replace(/\{([^}]*)\}/g,(typeof e=="object")?function(g,j){
        var h=e[j];
        return h==null&&d?g:h
        }:e)
    },
output:function(e,g){
    var d=[];
    e=e||"crystal";
    for(var f in (g||this)){
        d.push("var ",f,"=",e,".",f,";")
        }
        return d.join("")
    }
}
})(),(function(){
    var c={
        ie:0,
        opera:0,
        gecko:0,
        webkit:0,
        mobile:null
    };
    
    var b=navigator.userAgent,a;
    if((/KHTML/).test(b)){
        c.webkit=1
        }
        a=b.match(/AppleWebKit\/([^\s]*)/);
    if(a&&a[1]){
        c.webkit=parseFloat(a[1]);
        if(/ Mobile\//.test(b)){
            c.mobile="Apple"
            }else{
            a=b.match(/NokiaN[^\/]*/);
            if(a){
                c.mobile=a[0]
                }
            }
    }
if(!c.webkit){
    a=b.match(/Opera[\s\/]([^\s]*)/);
    if(a&&a[1]){
        c.opera=parseFloat(a[1]);
        a=b.match(/Opera Mini[^;]*/);
        if(a){
            c.mobile=a[0]
            }
        }else{
    a=b.match(/MSIE\s([^;]*)/);
    if(a&&a[1]){
        c.ie=parseFloat(a[1])
        }else{
        a=b.match(/Gecko\/([^\s]*)/);
        if(a){
            c.gecko=1;
            a=b.match(/rv:([^\s\)]*)/);
            if(a&&a[1]){
                c.gecko=parseFloat(a[1])
                }
            }
    }
}
}
return c
})());