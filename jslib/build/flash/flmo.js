$app.flmo={
    historyHash:"",
    histroyHashArr:[],
    init:function(){
        var b=(!window.location.hash)?"#home":window.location.hash;
        window.location.hash=b;
        this.historyHash=b;
        var a=this.addHistroy(b);
        if(a==true){
            this.monitor(b)
            }
        },
addHistroy:function(d){
    var c=false;
    var a=this.histroyHashArr;
    for(o in a){
        if(o==d){
            c=true;
            var b=new Date().getTime();
            if(a[d]<b-5*60*1000){
                return true
                }else{
                return false
                }
                break
        }
    }
    if(c==false){
    this.histroyHashArr[d]=new Date().getTime();
    return true
    }
},
checkHash:function(){
    var a=window.location.hash;
    if(a!=this.historyHash){
        this.init()
        }
    },
monitor:function(){
    var b=window.location.href;
    var c=document.referrer;
    var a=new Image();
    a.src="http://t.l.qq.com/ping?t=m&cpid="+appConfig.tamsid+"&url="+escape(b)+"&ref="+escape(c)
    }
};

var t=window.setInterval("$app.flmo.checkHash()",50);