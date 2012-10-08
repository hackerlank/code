$app.dom={
    hasClass:function(b,a){
        return a&&(" "+b.className+" ").indexOf(" "+a+" ")>-1
        },
    addClass:function(b,a){
        if(b.className===""){
            b.className=a
            }else{
            if(b.className!==""&&!this.hasClass(b,a)){
                b.className=b.className+" "+a
                }
            }
    },
removeClass:function(b,a){
    if(this.hasClass(b,a)){
        b.className=(" "+b.className+" ").replace(" "+a+" "," ").replace(/^ | $/g,"")
        }
    },
getElementsByClassName:function(f,b,c){
    if(!c){
        return[]
        }
        var d=[],g=c.getElementsByTagName(b);
    for(var e=0,a=g.length;e<a;e++){
        if(this.hasClass(g[e],f)){
            d[d.length]=g[e]
            }
        }
    return d
},
getPreviousSibling:function(a){
    while(a){
        a=a.previousSibling;
        if(a&&a.nodeType==1){
            return a
            }
        }
    return null
},
getNextSibling:function(a){
    while(a){
        a=a.nextSibling;
        if(a&&a.nodeType==1){
            return a
            }
        }
    return null
},
getChildren:function(e){
    var b=e.children||e.childNodes,c,a,d=[];
    a=b.length;
    if(a===0){
        return d
        }
        if(e.children){
        return b
        }else{
        for(c=0;c<a;c++){
            if(b[c]&&(b[c].nodeType!=1||(b[c].nodeType==1&&b[c].parentNode!=e))){
                continue
            }
            d[d.length]=b[c]
            }
        }
        return d
},
isChild:function(b,a){
    if(!b||!a){
        return"we need sun element object and parent element object."
        }
        if(a.tagName&&a.tagName.toLowerCase()=="body"){
        return true
        }while(b&&b.tagName&&b.tagName.toLowerCase()!="body"){
        if(b.parentNode==a){
            return true
            }
            b=b.parentNode
        }
        return false
    },
getStyle:function(c,b,a){
    if(c.currentStyle){
        return a?parseFloat(c.currentStyle[b].replace(/px|pt|em/ig,"")):c.currentStyle[b]
        }else{
        if(c.style[b]){
            return a?parseFloat(c.style[b].replace(/px|pt|em/ig,"")):c.style[b]
            }else{
            return a?parseFloat(window.getComputedStyle(c,null).getPropertyValue(b).replace(/px|pt|em/ig,"")):window.getComputedStyle(c,null).getPropertyValue(b)
            }
        }
},
setStyle:function(c,b,a){
    if(typeof(c)=="String"){
        c=$app.G(c)
        }
        c.style[b]=a
    }
};