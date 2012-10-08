$app.mo={
    mo_ping_url:"http://dp3.qq.com/play/",
    getUA:function(){
        var d={
            ie:0,
            opera:0,
            gecko:0,
            webkit:0,
            mobile:null
        };
        
        var c=navigator.userAgent;
        if((/KHTML/).test(c)){
            d.webkit=1
            }
            var a=c.match(/AppleWebKit\/([^\s]*)/);
        if(a&&a[1]){
            d.webkit=parseFloat(a[1]);
            if(/ Mobile\//.test(c)){
                d.mobile="Apple"
                }else{
                a=c.match(/NokiaN[^\/]*/);
                if(a){
                    d.mobile=a[0]
                    }
                }
        }
    if(!d.webkit){
    a=c.match(/Opera[\s\/]([^\s]*)/);
    if(a&&a[1]){
        d.opera=parseFloat(a[1]);
        a=c.match(/Opera Mini[^;]*/);
        if(a){
            d.mobile=a[0]
            }
        }else{
    a=c.match(/MSIE\s([^;]*)/);
    if(a&&a[1]){
        d.ie=parseFloat(a[1])
        }else{
        a=c.match(/Gecko\/([^\s]*)/);
        if(a){
            d.gecko=1;
            a=c.match(/rv:([^\s\)]*)/);
            if(a&&a[1]){
                d.gecko=parseFloat(a[1])
                }
            }
    }
}
}
for(var b in d){
    if(d[b]!=0){
        return b+d[b]
        }
    }
},
cutTime:function(a){
    var b=(a+"");
    return b.substring(b.length-6)
    },
domain:function(){
    s=location.href;
    try{
        var a=s.match(/http:\/\/([^/]*)\/|$/i);
        return a[1]
        }catch(b){
        return""
        }
    },
QosS:0,
ping:function(){
    var b=new Image(1,1);
    var d=this.mo_ping_url+"?ua="+this.getUA()+"&domain="+this.domain()+"&QosS="+this.QosS;
    var a=false;
    for(var c in this.stamps){
        if(this.stamps[c]){
            d+="&"+c+"="+this.stamps[c];
            var a=true
            }
        }
    if(a==false){
    return
}
b.src=d;
return this
},
stamps:[],
init:function(){
    this.QosS=this.cutTime(new Date().getTime())
    },
append:function(a){
    if(a=="first_screen"||a=="full_screen"){
        this.stamps[a]=this.cutTime(new Date().getTime())
        }
    }
};

$app.mo.init();
$app.addEvent(window,"load",function(){
    $app.mo.ping()
    });