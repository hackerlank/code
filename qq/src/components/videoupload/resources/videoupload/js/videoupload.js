/**
 * @fileOverview app jslib 上传组件
 * @param {args} vid from元素或input元素数组，用于模仿异步提交
 * @param {Function} callback 上传回调函数 
 * @author allenyu@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.upload = {
    init:function(el,func){
        this.el = el;
        this.callback = func || function(){};
    },
    uploadpic:function(el,func){
        this.init(el, func);
        if(el.value == ''){
            alert("请您选择要上传的图片！");
            return;
        }
        this._clearContext();
        if(typeof(args) == "string"){
            el = document.getElementById(el);
        }
        this.action = 'http://upload.act.qq.com/cgi-bin/up_pic';
        //创建iframe与表单
        this._creatIfram();
        //创建form
        this._creatForm();
    },
	uploadvideo:function(el,obj,func){
        this.init(el, func);
        if(el.value == ''){
            alert("请您选择要上传的图片！");
            return;
        }
        this._clearContext();
        if(typeof(args) == "string"){
            el = document.getElementById(el);
        }
        this.action = 'http://upload.act.qq.com/cgi-bin/up_video';
        //创建iframe与表单
        this._creatIfram();
        //创建form
        this._creatVideoForm(obj);
    },
    //创建iframe以及form
    _creatIfram:function(){
        if(document.getElementById("jslib_FormSubmit_iframe")){
            return;
        }
        var appUpload = $app.upload;
        var iframe = document.createElement('iframe');
        iframe.id = 'jslib_FormSubmit_iframe';
        iframe.name = 'jslib_FormSubmit_iframe';
        iframe.src = 'about:blank';
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
    // var iframe =  '<iframe id="jslib_FormSubmit_iframe" name="jslib_FormSubmit_iframe" src="about:blank" style="display:none;" onload="javascript:setTimeout(\'asyn.complete()\',100)"></iframe>'
    },
    _creatForm:function(){
        if(document.getElementById("file_upload_form")){
            return;
        }
        var objForm = document.createElement("form");
        objForm.action = this.action;
        objForm.target = "jslib_FormSubmit_iframe";
        objForm.encoding = "multipart/form-data";
        objForm.method = "post";
        objForm.id = "file_upload_form";
        objForm.style.display = "none";  
        
        //获取参数
        var params = this._getParam();
        if(params.ticket == 'notlogin'){
            $app.auth.login();
            return;
        }
        objForm.innerHTML = '<input id="uin" name="uin" type="hidden" value="'+params.uin+'"/>'+
        '<input id="actid" name="actid" type="hidden" value="'+params.actid+'"/>'+
        '<input id="ticket" name="ticket" type="hidden" value="'+params.ticket+'" />'+
        '<input id="callbackName" name="callbackName" type="hidden" value="$app.upload.callback"/>';
        //上传file加入form
        var elclone = this.el.cloneNode(true);
        elclone.name = 'fileField';
        objForm.appendChild(elclone);
        
        document.body.appendChild(objForm);
        objForm.submit();
        //this._clearContext();
    },
	_creatVideoForm:function(obj){
        if(document.getElementById("file_upload_form")){
            return;
        }
        var objForm = document.createElement("form");
        objForm.action = this.action;
        objForm.target = "jslib_FormSubmit_iframe";
        objForm.encoding = "multipart/form-data";
        objForm.method = "post";
        objForm.id = "file_upload_form";
        objForm.style.display = "none";  
        
        //获取参数
        var params = this._getVideoParam(obj);
        if(params.ticket == 'notlogin'){
            $app.auth.login();
            return;
        }
        objForm.innerHTML = '<input id="uin" name="uin" type="hidden" value="'+params.uin+'"/>'+
        '<input id="actid" name="actid" type="hidden" value="'+params.actid+'"/>'+
        '<input id="ticket" name="ticket" type="hidden" value="'+params.ticket+'" />'+
        '<input id="callbackName" name="callbackName" type="hidden" value="$app.upload.callback"/>';
        //上传file加入form
        var elclone = this.el.cloneNode(true);
        elclone.name = 'fileField';
        objForm.appendChild(elclone);
        
        document.body.appendChild(objForm);
        objForm.submit();
        //this._clearContext();
    },
    _clearContext:function(){
        var iframe = document.getElementById('jslib_FormSubmit_iframe');
        var jslib_form = document.getElementById("file_upload_form");
        if(iframe){
            document.body.removeChild(iframe);
        }
        if(jslib_form){
            document.body.removeChild(jslib_form);
        }
    },
    _getParam:function(){
        var url = '/fileupload/fileupload/ticket?dt=' + Date.parse(new Date());
        var param = {};
        var response = $app.ajax.sync(url);
        response = eval( "(" + response + ")" );
        console.log(response)
        if(response.code == 0){
            param.ticket = response.data.ticket;
            param.uin = $app.auth.getQQNum();
        }else if(response.code == "10102"){
            param.ticket = 'notlogin';
        }else{
            param.ticket = null;
        }
        param.actid = appConfig.tamsid;
        return param;
    },
	_getVideoParam:function(obj){
        var url = '/videoupload/videoupload/ticket?title='+obj.title+'&tags='+obj.tags+'&cat='+obj.cat+'&desc='+obj.desc+'&dt=' + Date.parse(new Date());
        var param = {};
        var response = $app.ajax.sync(url);
        response = eval( "(" + response + ")" );
        console.log(response)
        if(response.code == 0){
            param.ticket = response.data.ticket;
            param.uin = $app.auth.getQQNum();
        }else if(response.code == "10102"){
            param.ticket = 'notlogin';
        }else{
            param.ticket = null;
        }
        param.actid = appConfig.tamsid;
        return param;
    }
}