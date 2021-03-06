$app.scroll={
    _obj:null,
    _timer:null,
    _autoFlag:null,
    _moveLock:false,
    _comp:0,
    options:{
        speed:1,
        space:20,
        scrollWidth:642,
        interval:3000,
        fill:1,
        way:"right",
        auto:true
    },
    _extend:function(b,a){
        for(var c in a){
            b[c]=a[c]
            }
            return b
        },
    _getId:function(a){
        return typeof a=="object"?a:document.getElementById(a)
        },
    init:function(b,c){
        var a=this;
        this._obj=this._getId(b);
        this._options=this._extend(this._options,c);
        this._getId("list2").innerHTML=this._getId("list1").innerHTML;
        this._obj.scrollLeft=this._options.fill>=0?this._options.fill:this._getId("list1").scrollWidth-Math.abs(this._options.fill);
        this._obj.onmouseover=function(){
            clearInterval(a._autoFlag)
            };
            
        this._obj.onmouseout=function(){
            a.goAuto()
            };
            
        if(this._options.auto==true){
            this.goAuto()
            }
        },
goBack:function(){
    if(this._moveLock){
        return
    }
    clearInterval(this._autoFlag);
    this._moveLock=true;
    this._options.way="left";
    this._timer=setInterval("$app.scroll.goBackScroll();",this._options.speed)
    },
goNext:function(){
    clearInterval(this._timer);
    if(this._moveLock){
        return
    }
    clearInterval(this._autoFlag);
    this._moveLock=true;
    this._options.way="right";
    this.goNextScroll();
    this._timer=setInterval("$app.scroll.goNextScroll();",this._options.speed)
    },
goBackStop:function(){
    if(this._options.way=="right"){
        return
    }
    clearInterval(this._timer);
    if((this._obj.scrollLeft-this._options.fill)%this._options.scrollWidth!=0){
        this._comp=this._options.fill-(this._obj.scrollLeft%this._options.scrollWidth);
        this.compScr()
        }else{
        this._moveLock=false
        }
        this.goAuto()
    },
goNextStop:function(){
    if(this._options.way=="left"){
        return
    }
    clearInterval(this._timer);
    if(this._obj.scrollLeft%this._options.scrollWidth-(this._options.fill>=0?this._options.fill:this._options.fill+1)!=0){
        this._comp=this._options.scrollWidth-this._obj.scrollLeft%this._options.scrollWidth+this._options.fill;
        this.compScr()
        }else{
        this._moveLock=false
        }
        this.goAuto()
    },
goBackScroll:function(){
    if(this._obj.scrollLeft<=0){
        this._obj.scrollLeft=this._obj.scrollLeft+this._getId("list1").offsetWidth
        }
        this._obj.scrollLeft-=this._options.space
    },
goNextScroll:function(){
    if(this._obj.scrollLeft>=this._getId("list1").scrollWidth){
        this._obj.scrollLeft=this._obj.scrollLeft-this._getId("list1").scrollWidth
        }
        this._obj.scrollLeft+=this._options.space
    },
goAuto:function(){
    clearInterval(this._autoFlag);
    this._autoFlag=setInterval("$app.scroll.goNext();$app.scroll.goNextStop();",this._options.interval)
    },
compScr:function(){
    if(this._comp==0){
        this._moveLock=false;
        return
    }
    var a,c=this._options.speed,b=this._options.space;
    if(Math.abs(this._comp)<this._options.scrollWidth/2){
        b=Math.round(Math.abs(this._comp/this._options.space));
        if(b<1){
            b=1
            }
        }
    if(this._comp<0){
    if(this._comp<-b){
        this._comp+=b;
        a=b
        }else{
        a=-this._comp;
        this._comp=0
        }
        this._obj.scrollLeft-=a;
    setTimeout("$app.scroll.compScr()",c)
    }else{
    if(this._comp>b){
        this._comp-=b;
        a=b
        }else{
        a=this._comp;
        this._comp=0
        }
        this._obj.scrollLeft+=a;
    setTimeout("$app.scroll.compScr()",c)
    }
}
};