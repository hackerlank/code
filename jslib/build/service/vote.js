$app.vote=function(a,b){
    if(!$app.auth.isLogin()){
        alert("温馨提示：请先登录QQ！");
        $app.auth.login();
        return
    }else{
        loader("verifycode/verifycode",function(){
            var c=c||function(d){
                alert(d.message);
                if(d.code!=0){
                    return false
                    }else{
                    var e=parseInt($app.G("vote_"+a+"_count").innerHTML);
                    $app.G("vote_"+a+"_count").innerHTML=e+d.count
                    }
                };
            
        $app.verifycode.show("请输入验证码",function(e){
            var f=e.verifycode;
            var d=e.vid;
            var g=e.callback;
            loader("ajax/ajax",function(){
                var h=appConfig.ajaxVoteUrl;
                $app.ajax.post(h,{
                    vid:d,
                    verifycode:f
                },function(i){
                    g(i)
                    },"json")
                })
            },{
            vid:a,
            callback:c
        })
        })
    }
};