$app.debug=function(c,b){
    var j=typeof(c);
    var h="";
    var d={
        string:1,
        number:1,
        "boolean":1,
        "undefined":1
    };
    
    var k;
    if(d[j]||j=="function"||!!c==false){
        h=""+j+" "+c
        }else{
        if(j=="object"||j=="array"||j=="class"||j=="arguments"){
            for(var a in c){
                h+=typeof(c[a])+" "+a+"="+c[a]+"\n"
                }
            }else{
        if(j=="element"){
            h="element:\n";
            for(var f in c){
                h+=""+typeof(c[f])+" "+f+"="+c[f]+"\n"
                }
            }else{
        h="type of:"+j+":"+c
        }
    }
}
h=h.replace(/&/g,"&amp;");
h=h.replace(/</g,"&lt;");
h=h.replace(/>/g,"&gt;");
if(b==true){
    try{
        debugWindow=window.open("about:blank","$app.debug","width=800,height=600,scrollbars=1,resizable,status");
        debugWindow.document.write('<html><head><title>$app.debug output</title></head><body><h2>$app.debug Output</h2><div id="debugTag"></div></body></html>');
        k=debugWindow.document.getElementById("debugTag");
        k.innerHTML=("<b>"+(new Date()).toString()+"</b>:<pre>"+h+"</pre><hr/>")+k.innerHTML
        }catch(g){
        alert("$app.debug Output:\n"+h)
        }
    }else{
    alert("$app.debug Output:\n"+h)
    }
};