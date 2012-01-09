$app.advForm={
    checkForm:function(g,c,f,k){
        var a=$app.G(g);
        var l=a.getElementsByTagName("input");
        var j=a.getElementsByTagName("select");
        var h=a.getElementsByTagName("textarea");
        var b=[];
        for(var d=0,e=l.length;d<e;d++){
            b[l[d].name]=l[d]
            }
            for(var d=0,e=j.length;d<e;d++){
            b[j[d].name]=j[d]
            }
            for(var d=0,e=h.length;d<e;d++){
            b[h[d].name]=h[d]
            }
            $app.addEvent(a,"submit",function(E){
            if(document.all){
                E.returnValue=false
                }else{
                E.preventDefault()
                }
                var s="";
            var H=[];
            for(o in b){
                var v=b[o];
                var u=v.getAttribute("datatype");
                var A=v.value;
                switch(v.type){
                    case"checkbox":
                        var m=document.getElementsByName(v.name);
                        H[v.name]="";
                        for(var F=0,D=m.length;F<D;F++){
                        if(m[F].checked){
                            H[v.name]+=m[F].value+","
                            }
                        }
                    H[v.name]=H[v.name].substring(0,H[v.name].length-1);
                    A=H[v.name];
                    break;
                case"radio":
                    var m=document.getElementsByName(v.name);
                    for(var w=0,D=m.length;w<D;w++){
                    if(m[w].checked){
                        H[v.name]=m[w].value;
                        A=m[w].value;
                        break
                    }
                }
                if(H[v.name]==undefined){
                    A=""
                    }
                    break;
            default:
                H[v.name]=v.value;
                break
                }
                if(u!=null&&typeof(u)!="undefined"){
                var n=false;
                if(u.indexOf("norequire")>-1){
                    n=true;
                    u=u.replace("norequire ","");
                    A=A.replace(/^\s*(.*?)\s*$/,"$1")
                    }
                    var q=u.split(" ");
                var B=false;
                for(var C=0;C<q.length;C++){
                    var G=false;
                    if(q[C]=="unSafe"){
                        G=true
                        }
                        if(n==true){
                        if(A!=""){
                            if($app.form.validatorType[q[C]].test(A)==G){
                                B=true
                                }
                            }
                    }else{
                    if($app.form.validatorType[q[C]].test(A)==G){
                        B=true
                        }
                    }
            }
            if(B==true){
            s=v.getAttribute("msg");
            $app.G(c).innerHTML='<span style="color:red">'+s+"</span>";
            $app.G(c).style.display="block";
            return
        }
    }
}
var z="";
for(o in H){
    if(o!=null&&o!=""){
        z+="&"+o+"="+H[o]
        }
    }
if(z.length==0){
    return
}
z=z.substring(1);
var x=new Date().getTime();
var p=a.getAttribute("action");
var y=f.toLowerCase();
if(y=="get"){
    var r=document.getElementById(c);
    if(r){
        r.innerHTML='<span style="color:red">载入中，请稍后...</span>';
        r.style.display="block"
        }
        loader("ajax/ajax",function(){
        $app.ajax.getJSON(p+"?dt="+x+"&"+z,function(i){
            k(i)
            })
        })
    }else{
    if(y=="post"){
        var r=document.getElementById(c);
        if(r){
            r.innerHTML='<span style="color:red">载入中，请稍后...</span>';
            r.style.display="block"
            }
            loader("ajax/ajax",function(){
            $app.ajax.post(p+"?dt="+x,H,function(i){
                k(i)
                },"json")
            })
        }else{
        if(y=="jsonp"){
            var r=document.getElementById(c);
            if(r){
                r.innerHTML='<span style="color:red">载入中，请稍后...</span>';
                r.style.display="block"
                }
                loader("ajax/ajax",function(){
                $app.ajax.getJSONP(p+"?"+z,function(i){
                    k(i)
                    })
                })
            }else{
            a.submit()
            }
        }
}
})
},
validatorType:{
    require:/[^(^\s*)|(\s*$)]/,
    email:/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
    phone:/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/,
    mobile:/^0{0,1}1[0-9]{10}$/,
    tel:/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$|^0{0,1}1[0-9]{10}$/,
    currency:/^\d+(\.\d+)?$/,
    number:/^\d+$/,
    zip:/^[0-9]\d{5}$/,
    ip:/^[\d\.]{7,15}$/,
    idcard:/^\d{17}[0-9Xx]$/,
    qq:/^[1-9]\d{4,8}$/,
    integer:/^[-\+]?\d+$/,
    english:/^[A-Za-z]+$/,
    chinese:/^[\u0391-\uFFE5]+$/,
    userName:/^[A-Za-z0-9_]{3,}$/i,
    unSafe:/[<>\?\#\$\*\&;\\\/\[\]\{\}=\(\)\.\^%,]/
}
};