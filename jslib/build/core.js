var $app={
    version:"1.4.2",
    B:{
        version:(window.navigator.userAgent.toLowerCase().match(/.+(?:pe6?|or|ox|it|ra|ie|rv|me)[\/: ]([\d.]+)/)||[])[1],
        name:/(netscape|firefox|opera|msie|safari|konqueror|chrome)/.test(window.navigator.userAgent.toLowerCase())?RegExp.$1:(/webkit/.test(window.navigator.userAgent.toLowerCase())?"safari":(/mozilla/.test(window.navigator.userAgent.toLowerCase())?"mozilla":"unknown"))
        },
    G:function(a){
        return"string"==typeof a?document.getElementById(a):a
        },
    addEvent:function(d,c,b){
        var a=d;
        if(typeof(d)=="string"){
            d=document.getElementById(d)
            }else{
            a=d.id
            }
            if(this._addEventArr[a]==1){
            return
        }else{
            this._addEventArr[a]=1
            }
            if(d.addEventListener){
            d.addEventListener(c,b,false)
            }else{
            if(d.attachEvent){
                d.attachEvent("on"+c,function(){
                    return b.apply(d,new Array(window.event))
                    })
                }
            }
    },
_addEventArr:[],
now:function(){
    return new Date().getTime()
    },
css:function(b){
    var a=document.getElementsByTagName("head").item(0);
    var c=document.createElement("link");
    c.href=b;
    c.rel="stylesheet";
    c.type="text/css";
    a.appendChild(c)
    }
};