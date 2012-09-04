function userLoginDialog()
{
    var modalDialogId = "logindialogdiv";
    var modaldiv = '<div class="modal fade" id="'+modalDialogId+'" style="display:none;">'+
        '<div class="modal-header">'+
        '<a class="close" onclick="javascript:closeModalDialog(\''+modalDialogId+'\');">x</a>'+
        '<h3><i class="icon-user" style="margin-top:5px;"></i> 用户登录</h3></div>'+
        '<div class="modal-body">'+
        '<div class="form-horizontal"><filedset>'+
        '<div class="control-group"><label for="user_name" class="control-label">用户名</label> <div class="controls"><input type="text" name="user_name" id="user_name" value="" /> </div></div>'+
        '<div class="control-group"><label for="user_pwd" class="control-label">密&nbsp;&nbsp;码</label><div class="controls"><input type="password" name="user_pwd" id="user_pwd" value="" /></div></div>'+
        '</filedset></div>'+
        '</div>'+
        '<div class="alert hide"><button href="javascript:;" onclick="javascript:closeAlert();" class="close">x</button><span id="errmsg"></span></div>'+
        '<div class="modal-footer"><button class="btn-large btn-primary" onclick="javascript:userLogin();"> &nbsp;&nbsp;登&nbsp;&nbsp;录 &nbsp;&nbsp;</button></div>'+
        '</div>';
    var bgdiv = '<div class="modal-backdrop fade in"></div>';
    $("body").append(modaldiv).append(bgdiv);
    $("#"+modalDialogId).show().addClass("in");
}

function userLogin()
{
    var user_name = $("#user_name").val();
    var user_pwd = $("#user_pwd").val();
    user_name = user_name.replace(/\s+/gm,'')
    user_pwd = user_pwd.replace(/\s+/gm,'');
    
    if ('' == user_name) {
        $('#errmsg').text("请输入用户名");
        $('#user_name').val('').focus();
        return false;
    }

    if ('' == user_pwd) {
        $('#errmsg').text("请输入密码");
        $('#user_pwd').val('').focus();
        return false;
    }
    
    $.post('/admin/login', {'user_name': user_name, 'user_pwd': user_pwd}, function(data){
        if (data.err) {
            $('#user_name').val('').focus();
            $('#user_pwd').val('');
            $('#errmsg').text(data.msg);
            $('.alert').show();
        } else {
            window.location.href='/admin/';
        }
    }, 'json');
}
function closeAlert()
{
    $('.alert').hide();
}
$(function(){
	$("input").blur(function(){$(this).removeClass("focused");});
	$("input").focus(function(){$(this).addClass("focused");});
	
	//监听enter
	$(document).bind('keypress', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if(code == 13) {
			var username = $("#user_name").val();
			var userpwd = $("#user_pwd").val();
			username = username.replace(/\s+/gm,'')
			userpwd = userpwd.replace(/\s+/gm,'');
			
			if (0 == username.length) {$("#user_name").val("").focus();return false;}
			if (0 == userpwd.length) {$("#user_pwd").val("").focus();return false;}
			
			userLogin();
		}
	});
	
});
