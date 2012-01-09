$app.event={
    stopEvent:function(a){
        this.stopPropagation(a);
        this.preventDefault(a)
        },
    stopPropagation:function(a){
        if(a.stopPropagation){
            a.stopPropagation()
            }else{
            a.cancelBubble=true
            }
        },
preventDefault:function(a){
    if(a.preventDefault){
        a.preventDefault()
        }else{
        a.returnValue=false
        }
    },
getEvent:function(b){
    var a=b||window.event;
    if(!a){
        var d=this.getEvent.caller;
        while(d){
            a=d.arguments[0];
            if(a&&Event==a.constructor){
                break
            }
            d=d.caller
            }
        }
    return a
},
getTarget:function(c,b){
    var a=c.target||c.srcElement;
    return this.resolveTextNode(a)
    },
getXY:function(a){
    return{
        x:a.pageX?a.pageX:a.clientX+document.documentElement.scrollLeft,
        y:a.pageY?a.pageY:a.clientY+document.documentElement.scrollTop
        }
    }
};