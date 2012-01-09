function L_calendar(){}
L_calendar.prototype={
    newName:"",
    clickObject:null,
    inputObject:null,
    inputDate:null,
    L_TheYear:new Date().getFullYear(),
    L_TheMonth:new Date().getMonth()+1,
    L_WDay:new Array(42),
    monHead:new Array(31,28,31,30,31,30,31,31,30,31,30,31),
    getY:function(){
        var a;
        if(arguments.length>0){
            a=arguments[0]
        }else{
            a=this.clickObject
        }
        if(a!=null){
            var b=a.offsetTop;
            while(a=a.offsetParent){
                b+=a.offsetTop
            }
            return b
        }else{
            return 0
        }
    },
    getX:function(){
        var a;
        if(arguments.length>0){
            a=arguments[0]
        }else{
            a=this.clickObject
        }
        if(a!=null){
            var b=a.offsetLeft;
            while(a=a.offsetParent){
                b+=a.offsetLeft
            }
            return b
        }else{
            return 0
        }
    },
    createHTML:function(){
        var a="";
        a+='<div id="L_calendar">\r\n';
        a+='<div id="L_calendar-year-month">';
        a+='<div id="L_calendar-PrevM" onclick="'+this.newName+'.prevM()" title="前一月">&lt;</div>';
        a+='<div id="L_calendar-year"></div>';
        a+='<div id="L_calendar-month"></div>';
        a+='<div id="L_calendar-NextM" onclick="'+this.newName+'.nextM()" title="后一月">&gt;</div>';
        a+="</div>\r\n";
        a+='<div id="L_calendar-day">\r\n';
        a+="<ul>\r\n";
        for(var b=0;b<this.L_WDay.length;b++){
            a+='<li id="L_calendar-day_'+b+'" style="background:#8DB2E3" onmouseover="this.style.background=\'#8DB2E3\'"  onmouseout="this.style.background=\'#FFFFFF\'"></li>\r\n'
        }
        a+="</ul>\r\n";
        a+="</div>\r\n";
        a+="</div>\r\n";
        document.getElementById("L_DateLayer").innerHTML=a;
        document.getElementById("L_DateLayer").style.display="block"
    },
    insertHTML:function(b,a){
        document.getElementById(b).innerHTML=a
    },
    writeHead:function(b,a){
        this.insertHTML("L_calendar-year",b+" 年");
        this.insertHTML("L_calendar-month",a+" 月")
    },
    isPinYear:function(a){
        if(0==a%4&&((a%100!=0)||(a%400==0))){
            return true
        }else{
            return false
        }
    },
    getMonthCount:function(a,b){
        var d=this.monHead[b-1];
        if((b==2)&&this.isPinYear(a)){
            d++
        }
        return d
    },
    prevM:function(){
        if(this.L_TheMonth>1){
            this.L_TheMonth--
        }else{
            this.L_TheYear--;
            this.L_TheMonth=12
        }
        this.setDay(this.L_TheYear,this.L_TheMonth)
    },
    nextM:function(){
        if(this.L_TheMonth==12){
            this.L_TheYear++;
            this.L_TheMonth=1
        }else{
            this.L_TheMonth++
        }
        this.setDay(this.L_TheYear,this.L_TheMonth)
    },
    setDay:function(e,b){
        this.writeHead(e,b);
        this.L_TheYear=e;
        this.L_TheMonth=b;
        for(var c=0;c<42;c++){
            this.L_WDay[c]=""
        }
        var h=1,g=1,a=new Date(e,b-1,1).getDay();
        for(c=0;c<a;c++){
            this.L_WDay[c]=this.getMonthCount(b==1?e-1:e,b==1?12:b-1)-a+c+1
        }
        for(c=a;h<this.getMonthCount(e,b)+1;c++){
            this.L_WDay[c]=h;
            h++
        }
        for(c=a+this.getMonthCount(e,b);c<42;c++){
            this.L_WDay[c]=g;
            g++
        }
        for(c=0;c<42;c++){
            var j=document.getElementById("L_calendar-day_"+c+"");
            var d,f;
            if(this.L_WDay[c]!=""){
                if(c<a){
                    j.innerHTML='<span style="color:gray">'+this.L_WDay[c]+"</span>";
                    d=(b==1?12:b-1);
                    f=this.L_WDay[c]
                }else{
                    if(c>=a+this.getMonthCount(e,b)){
                        j.innerHTML='<span style="color:gray">'+this.L_WDay[c]+"</span>";
                        d=(b==1?12:b+1);
                        f=this.L_WDay[c]
                    }else{
                        j.innerHTML='<b style="color:#000">'+this.L_WDay[c]+"</b>";
                        d=(b==1?12:b);
                        f=this.L_WDay[c]
                    }
                }
                if(document.all){
                    j.onclick=Function(this.newName+".dayClick("+d+","+f+")")
                }else{
                    j.setAttribute("onclick",this.newName+".dayClick("+d+","+f+")")
                }
                j.title=d+" 月"+f+" 日";
                j.style.background="#FFFFFF";
                if(this.inputDate!=null){
                    if(e==this.inputDate.getFullYear()&&d==this.inputDate.getMonth()+1&&f==this.inputDate.getDate()){
                        j.style.background="#8DB2E3"
                    }
                }else{
                    j.style.background=(e==new Date().getFullYear()&&d==new Date().getMonth()+1&&f==new Date().getDate())?"#8DB2E3":"#FFFFFF"
                }
            }
        }
    },
    dayClick:function(b,a){
        var c=this.L_TheYear;
        if(b<1){
            c--;
            b=12+b
        }else{
            if(b>12){
                c++;
                b=b-12
            }
        }
        if(b<10){
            b="0"+b
        }
        if(this.clickObject){
            if(!a){
                return
            }
            if(a<10){
                a="0"+a
            }
            this.inputObject.value=c+"-"+b+"-"+a;
            this.closeLayer()
        }else{
            this.closeLayer();
            alert("您所要输出的控件对象并不存在！")
        }
    },
    setDate:function(){
        if(arguments.length<1||arguments.length>2){
            alert("传入参数错误！");
            return
        }
        this.inputObject=arguments[0];
        this.clickObject=arguments[0];
        var b=/^(\d+)-(\d{1,2})-(\d{1,2})$/;
        if(arguments.length==2){
            var c=arguments[1].match(b)
        }else{
            var c=this.inputObject.value.match(b)
        }
        if(c!=null){
            c[2]=c[2]-1;
            var g=new Date(c[1],c[2],c[3]);
            if(g.getFullYear()==c[1]&&g.getMonth()==c[2]&&g.getDate()==c[3]){
                this.inputDate=g
            }else{
                this.inputDate=""
            }
            this.L_TheYear=c[1];
            this.L_TheMonth=c[2]+1
        }else{
            this.L_TheYear=new Date().getFullYear();
            this.L_TheMonth=new Date().getMonth()+1
        }
        this.createHTML();
        var f=this.getY();
        var e=this.getX();
        var a=document.getElementById("L_DateLayer");
        a.style.top=f+this.clickObject.clientHeight+5+"px";
        a.style.left=e+"px";
        a.style.display="block";
        if(document.all){
            document.getElementById("L_calendar").style.width="128px";
            document.getElementById("L_calendar").style.height="130px"
        }else{
            document.getElementById("L_calendar").style.width="126px";
            document.getElementById("L_calendar").style.height="130px";
            a.style.width="126px";
            a.style.height="130px"
        }
        this.setDay(this.L_TheYear,this.L_TheMonth)
    },
    closeLayer:function(){
        try{
            var a=document.getElementById("L_DateLayer");
            if(arguments[0].id=="L_calendar-PrevM"||arguments[0].id=="L_calendar-NextM"){
                a.style.display="block"
            }else{
                if((a.style.display==""||a.style.display=="block")&&arguments[0]!=this.ClickObject&&arguments[0]!=this.inputObject){
                    a.style.display="none"
                }
            }
        }catch(b){}
    }
};

$app.css(appConfig.rootPath+"src/calendar/simple.css");
var mainDiv=document.createElement("div");
mainDiv.id="L_DateLayer";
mainDiv.style.display="none";
mainDiv.style.position="absolute";
mainDiv.style.width="128px";
mainDiv.style.height="128px";
mainDiv.style.zIndex="999";
document.body.appendChild(mainDiv);
$app.calendar=new L_calendar();
$app.calendar.newName="$app.calendar";
document.onclick=function(b){
    b=window.event||b;
    var a=b.srcElement||b.target;
    $app.calendar.closeLayer(a)
};