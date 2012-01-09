$app.follow={
    followUrl:"http://t.act.qq.com/follow/default/",
    cancelUrl:"http://t.act.qq.com/unfollow/default/",
    _default:function(a){
        alert(a.msg)
        },
    listen:function(c,b){
        var a=this.followUrl+"?u="+encodeURIComponent(c);
        b=b||this._default;
        loader("ajax/ajax",function(){
            $app.ajax.getJSONP(a,b)
            })
        },
    cancel:function(c,b){
        var a=this.cancelUrl+"?u="+encodeURIComponent(c);
        b=b||this._default;
        loader("ajax/ajax",function(){
            $app.ajax.getJSONP(a,b)
            })
        }
    };