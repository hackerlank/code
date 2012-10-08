$app.brith={
    init:function(o_year,o_month,o_day,s_year,s_month,s_day,fn){
        var o_year=document.getElementById(o_year);
        var o_month=document.getElementById(o_month);
        var o_day=document.getElementById(o_day);
        if(s_year!=""){
            var y=0;
            var yindex=0;
            for(var i=2010;i>=1949;i--){
                o_year.options[y]=new Option(i,i);
                if(i==s_year){
                    yindex=y
                    }
                    y++
            }
            o_year.selectedIndex=yindex
            }else{
            o_year.options[0]=new Option("请选择","");
            var y=1;
            for(var i=2010;i>=1949;i--){
                o_year.options[y]=new Option(i,i);
                y++
            }
            }
            if(s_month!=""){
        o_month.options.length=0;
        for(var i=0;i<12;i++){
            o_month.options[i]=new Option(i+1,i+1)
            }
            o_month.selectedIndex=s_month-1;
        if(s_month==1||s_month==3||s_month==5||s_month==7||s_month==8||s_month==10||s_month==12){
            var d=31
            }else{
            if(s_month==2){
                if((s_year%4==0&&s_year%100!=0)||(s_year%100==0)){
                    var d=29
                    }else{
                    var d=28
                    }
                }else{
            var d=30
            }
        }
}else{
    o_month.options[0]=new Option("请选择","")
    }
    if(s_day!=""){
    o_day.options.length=0;
    for(var i=0;i<d;i++){
        o_day.options[i]=new Option(i+1,i+1)
        }
        o_day.selectedIndex=s_day-1
    }else{
    o_day.options[0]=new Option("请选择","")
    }
    this.AddEvent(o_year,"change",function(){
    var year=o_year.options[o_year.selectedIndex].value;
    var month=o_month.options[o_month.selectedIndex].value;
    if(month==""){
        y=1;
        for(var i=1;i<=12;i++){
            o_month.options[y]=new Option(i,i);
            y++
        }
        return
    }
    var d=30;
    if(month==1||month==3||month==5||month==7||month==8||month==10||month==12){
        d=31
        }else{
        if(month==2){
            if((year%4==0&&year%100!=0)||(year%100==0)){
                d=29
                }else{
                d=28
                }
            }else{
        d=30
        }
    }
y=0;
o_day.options.length=0;
for(var i=1;i<=d;i++){
    o_day.options[y]=new Option(i,i);
    y++
}
if(fn){
    eval(fn+"();")
    }
});
this.AddEvent(o_month,"change",function(){
    var year=o_year.options[o_year.selectedIndex].value;
    var month=o_month.options[o_month.selectedIndex].value;
    var d=30;
    if(month==1||month==3||month==5||month==7||month==8||month==10||month==12){
        d=31
        }else{
        if(month==2){
            if((year%4==0&&year%100!=0)||(year%100==0)){
                d=29
                }else{
                d=28
                }
            }else{
        d=30
        }
    }
o_day.options.length=0;
y=0;
for(var i=1;i<=d;i++){
    o_day.options[y]=new Option(i,i);
    y++
}
if(fn){
    eval(fn+"();")
    }
});
this.AddEvent(o_day,"change",function(){
    if(fn){
        eval(fn+"();")
        }
    })
},
AddEvent:function(c,b,a){
    if(typeof(c)=="string"){
        c=document.getElementById(c)
        }
        if(c.addEventListener){
        c.addEventListener(b,a,false)
        }else{
        if(c.attachEvent){
            c.attachEvent("on"+b,function(){
                return a.apply(c,new Array(window.event))
                })
            }
        }
}
};