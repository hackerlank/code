$app.form={
    checkForm:function(f,e,j){
        var a=$app.G(f);
        var k=a.getElementsByTagName("input");
        var h=a.getElementsByTagName("select");
        var g=a.getElementsByTagName("textarea");
        var b=[];
        for(var c=0,d=k.length;c<d;c++){
            b[k[c].name]=k[c]
            }
            for(var c=0,d=h.length;c<d;c++){
            b[h[c].name]=h[c]
            }
            for(var c=0,d=g.length;c<d;c++){
            b[g[c].name]=g[c]
            }
            $app.addEvent(a,"submit",function(x){
            if(document.all){
                x.returnValue=false
                }else{
                x.preventDefault()
                }
                var B="";
            var q=[];
            for(o in b){
                var p=b[o];
                var r=p.getAttribute("datatype");
                var s=p.value;
                switch(p.type){
                    case"checkbox":
                        var z=document.getElementsByName(p.name);
                        q[p.name]="";
                        for(var A=0,v=z.length;A<v;A++){
                        if(z[A].checked){
                            q[p.name]+=z[A].value+","
                            }
                        }
                    q[p.name]=q[p.name].substring(0,q[p.name].length-1);
                    s=q[p.name];
                    break;
                case"radio":
                    var z=document.getElementsByName(p.name);
                    for(var C=0,v=z.length;C<v;C++){
                    if(z[C].checked){
                        q[p.name]=z[C].value;
                        s=z[C].value;
                        break
                    }
                }
                if(q[p.name]==undefined){
                    s=""
                    }
                    break;
            default:
                q[p.name]=p.value;
                break
                }
                if(r!=null&&typeof(r)!="undefined"){
                var l=false;
                if(r.indexOf("norequire")>-1){
                    l=true;
                    r=r.replace("norequire ","");
                    s=s.replace(/^\s*(.*?)\s*$/,"$1")
                    }
                    var u=r.split(" ");
                var y=false;
                for(var n=0;n<u.length;n++){
                    var w=false;
                    if(u[n]=="unSafe"){
                        w=true
                        }
                        if(l==true){
                        if(s!=""){
                            if($app.form.validatorType[u[n]].test(s)==w){
                                y=true
                                }
                            }
                    }else{
                    if($app.form.validatorType[u[n]].test(s)==w){
                        y=true
                        }
                    }
            }
            if(y==true){
            B+=p.getAttribute("msg")+"\n"
            }
        }
}
if(B.length>0){
    alert(B);
    return false
    }
    if(e.toLowerCase()=="post"){
    var m=a.getAttribute("action");
    $app.form.ajaxSubmit(m,"POST",q,j)
    }else{
    if(e.toLowerCase()=="get"){
        var m=a.getAttribute("action");
        $app.form.ajaxSubmit(m,"GET",q,j)
        }else{
        a.submit()
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
    qq:/^[1-9]\d{4,9}$/,
    integer:/^[-\+]?\d+$/,
    english:/^[A-Za-z]+$/,
    chinese:/^[\u0391-\uFFE5]+$/,
    userName:/^[A-Za-z0-9_]{3,}$/i,
    unSafe:/[<>\?\#\$\*\&;\\\/\[\]\{\}=\(\)\.\^%,]/
},
ajaxSubmit:function(url,type,data,callback){
    var datastr="";
    for(o in data){
        if(o!=null&&o!=""){
            datastr+="&"+o+"="+data[o]
            }
        }
    if(datastr.length==0){
    return
}
datastr=datastr.substring(1);
var a=window.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP"):new XMLHttpRequest();
with(a){
    open(type,url+"?"+datastr,true);
    if(type=="POST"){
        setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        send(datastr)
        }else{
        send(null)
        }
        onreadystatechange=function(){
        if(readyState==4&&status==200){
            if(responseText.length>0){
                eval("var data ="+responseText)
                }else{
                var data=""
                }
                callback(data)
            }
        }
}
}
};