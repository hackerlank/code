$app.ajaxQueue={
    _queueArr:[],
    _nowIndex:0,
    _ajaxCount:0,
    _addQueue:function(a){
        this._queueArr.push(a);
        this._ajaxCount++
    },
    _runAjax:function(d){
        var b=d.url;
        var a=d.success;
        var c=d.type;
        loader("ajax/ajax",function(){
            $app.ajax.get(b,function(f){
                a(f);
                $app.ajaxQueue.callback()
                },c)
            })
        },
    run:function(a){
        for(i in a){
            this._addQueue(a[i])
            }
            this._nowIndex=0;
        this.callback()
        },
    callback:function(){
        if(this._nowIndex>=this._ajaxCount){
            return
        }
        var a=this._queueArr[this._nowIndex];
        this._runAjax(a);
        this._nowIndex++
    }
};