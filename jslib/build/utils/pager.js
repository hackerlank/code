$app.pager=function(k,a,e,g,j,h){
    var c='<div class="pagination">';
    var h=parseInt(h)>0?parseInt(h):10;
    if(parseInt(j)==0||parseInt(j)=="NaN"){
        return
    }
    var f=Math.ceil(j/h);
    var g=parseInt(g)>0?parseInt(g):1;
    var l="";
    for(o in e){
        if(typeof e[o]=="string"){
            l+="'"+e[o]+"',"
            }else{
            l+=e[o]+","
            }
        }
    if(f>10){
    if((g-5)>0&&g<f-5){
        var b=g-5;
        var d=g+5
        }else{
        if(g>=(f-5)){
            var b=f-10;
            var d=f
            }else{
            var b=1;
            var d=10
            }
        }
}else{
    var b=1;
    var d=f
    }
    if(g>1){
    c+='<a href="javascript:'+a+"("+l+'1);" title="第一页" class="page-start">«</a>'
    }else{
    c+='<span class="page-disabled">«</span> '
    }
    if(g>1){
    c+='<a href="javascript:'+a+"("+l+(g-1)+');" title="上一页" class="page-start">‹</a>'
    }else{
    c+='<span class="page-disabled">‹</span>'
    }
    for(i=b;i<=d;i++){
    if(i==g){
        c+='<a href="javascript:;" class="page-cur">'+g+"</a>"
        }else{
        c+='<a href="javascript:'+a+"("+l+i+')">'+i+"</a>"
        }
    }
if(f>1&&g<f){
    c+='<a title="下一页" href="javascript:'+a+"("+l+(g+1)+');" class="page-end">›</a>'
    }else{
    c+='<span class="page-disabled">›</span>'
    }
    if(g<f){
    c+='<a title="下一页" href="javascript:'+a+"("+l+f+');" class="page-end">»</a>'
    }else{
    c+='<span class="page-disabled">»</span>'
    }
    c+="</div>";
$app.G(k).innerHTML=c
};