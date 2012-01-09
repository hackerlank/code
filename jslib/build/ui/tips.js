$app.tips=function(g,b){
    var d=navigator.userAgent.toLowerCase().match(/msie ([\d.]+)/);
    if(d){
        var f=d[1]
        }else{
        var f=0
        }
        var e=document.getElementById(g);
    e.style.display="block";
    if(f=="6.0"){
        e.style.top=(document.documentElement.clientHeight-e.clientHeight)+"px";
        e.style.position="absolute"
        }else{
        e.style.bottom=0;
        e.style.position="fixed"
        }
        e.style.right="0";
    e.style.zIndex="99";
    if(b==null){
        b={
            width:15,
            height:15,
            right:3,
            top:3
        }
    }
    var c=document.createElement("a");
    c.id="closebtn";
    if(d){
    c.innerHTML="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    c.style.lineHeight=b.height+"px";
    c.style.overflow="hidden"
    }
    c.style.display="block";
c.style.position="absolute";
c.style.cursor="pointer";
c.style.right=b.right+"px";
c.style.top=b.top+"px";
c.style.width=b.width+"px";
c.style.height=b.height+"px";
c.style.zIndex="100";
c.onclick=function(){
    document.getElementById(g).style.display="none"
    };
    
e.appendChild(c);
    if(f=="6.0"){
    window.onscroll=function(){
        var a=document.getElementById(g);
        a.style.top=(document.documentElement.clientHeight+document.documentElement.scrollTop-a.clientHeight)+"px"
        }
    }
};