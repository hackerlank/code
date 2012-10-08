$app.exchange={
    code:function(a,b){
        if(!$app.auth.isLogin()){
            alert("温馨提示：请先登录QQ！");
            $app.auth.login();
            return
        }else{
            if($app.util.trim(a)==""){
                alert("温馨提示：请输入兑换码！");
                return
            }else{
                loader("verifycode/verifycode",function(){
                    var c=c||$app.exchange._callback;
                    $app.verifycode.show("请输入验证码",function(d){
                        var f=d.verifycode;
                        var e=d.exCode;
                        var g=d.callback;
                        loader("dialog/dialog",function(){
                            $app.dialog.show({
                                title:"温馨提示",
                                content:"兑换中，请稍后...",
                                width:400,
                                height:100,
                                mask:true,
                                callback:""
                            });
                            loader("ajax/ajax",function(){
                                var h=appConfig.ajaxExchangeUrl;
                                $app.ajax.post(h,{
                                    exCode:e,
                                    verifycode:f
                                },function(i){
                                    $app.dialog.close();
                                    g(i)
                                    },"json")
                                })
                            })
                        },{
                        excode:a,
                        callback:c
                    })
                    })
                }
            }
    },
score:function(b,a){
    if(!$app.auth.isLogin()){
        alert("温馨提示：请先登录QQ！");
        $app.auth.login();
        return
    }else{
        if(parseInt(b)==0){
            alert("温馨提示：请先选择需要兑换的物品！");
            return
        }else{
            loader("verifycode/verifycode",function(){
                var c=c||$app.exchange._callback;
                $app.verifycode.show("请输入验证码",function(d){
                    var f=d.verifycode;
                    var e=d.prizeid;
                    var g=d.callback;
                    loader("dialog/dialog",function(){
                        $app.dialog.show({
                            title:"温馨提示",
                            content:"兑换中，请稍后...",
                            width:400,
                            height:100,
                            mask:true,
                            callback:""
                        });
                        loader("ajax/ajax",function(){
                            var h=appConfig.ajaxExchangeUrl;
                            $app.ajax.post(h,{
                                prizeid:e,
                                verifycode:f
                            },function(i){
                                $app.dialog.close();
                                g(i)
                                },"json")
                            })
                        })
                    },{
                    prizeid:b,
                    callback:c
                })
                })
            }
        }
},
_callback:function(a){
    alert(a.message);
    if(a.code!=0){
        return false
        }else{
        return true
        }
    }
};