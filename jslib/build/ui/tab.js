$app.tab={
    init:function(c){
        var d=document.getElementById(c).children.length;
        window.tabOn="tab_1";
        for(var a=1;a<=d;a++){
            if(a==1){
                document.getElementById("tab_1").className="on";
                document.getElementById("panel_1").style.display="block"
                }else{
                document.getElementById("tab_"+a).className="";
                document.getElementById("panel_"+a).style.display="none"
                }
                var b=document.getElementById("tab_"+a);
            b.style.cursor="pointer";
            $app.addEvent(b,"click",function(g){
                if(g.target){
                    targ=g.target
                    }else{
                    if(g.srcElement){
                        targ=g.srcElement
                        }
                    }
                if(targ.nodeType==3){
                targ=targ.parentNode
                }
                if(targ.id==window.tabOn){
                return
            }
            targ.className="on";
            document.getElementById(window.tabOn).className="";
                var f=window.tabOn.substr(targ.id.indexOf("_")+1);
                var h=document.getElementById("panel_"+f);
                h.style.display="none";
                f=targ.id.substr(targ.id.indexOf("_")+1);
                h=document.getElementById("panel_"+f);
                h.style.display="block";
                window.tabOn=targ.id
                })
        }
        }
};