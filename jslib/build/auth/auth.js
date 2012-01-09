$app.auth={
    _LoginType:appConfig.runMode=="dev"?"dev":"qq",
    login:function(a){
        loader("auth/"+this._LoginType+"login",function(){
            $app.login.loginQQ(a)
            })
        },
    logout:function(a){
        loader("auth/"+this._LoginType+"login",function(){
            $app.login.logoutQQ(a)
            })
        },
    relogin:function(a){
        this.logout();
        this.login(a)
        },
    isLogin:function(){
        if(this._LoginType=="other"){
            return true
            }else{
            var b,a;
            b=$app.util.cookie("uin");
            a=$app.util.cookie("skey");
            if(b&&b.length>4&&a&&a.length>0){
                return true
                }
                return false
            }
        },
getLoginInfo:function(){
    var a=$app.util.cookie("uin");
    a=a.substr(1);
    a++;
    a--;
    return{
        uin:a
    }
},
getQQNum:function(){
    var a=$app.util.cookie("uin");
    a=a.substr(1);
    a++;
    a--;
    return a
    }
};