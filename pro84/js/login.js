$(function(){
	$("input").blur(function(){$(this).removeClass("focused");});
	$("input").focus(function(){$(this).addClass("focused");});
	
	//监听enter
	$(document).bind('keypress', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if(code == 13) {
			var username = $("#username").val();
			var userpwd = $("#userpwd").val();
			username = username.replace(/\s+/gm,'')
			userpwd = userpwd.replace(/\s+/gm,'');
			
			if (0 == username.length) {$("#username").val("").focus();return false;}
			if (0 == userpwd.length) {$("#userpwd").val("").focus();return false;}
			
			login();
		}
	});
	
	$("#loginsub").live('click',function(){login();});
});
function login(){
	var name = $("#username").val();
	var pwd = $("#userpwd").val();
	name = name.replace(/\s+/gm,'');
	pwd = pwd.replace(/\s+/gm,'');
	
	if (0 == name.length) {
		$("#username").val("").focus();
		return false;
	}
	if (0 == pwd.length) {
		$("#userpwd").val("").focus();
		return false;
	}
	var postdata = {'name': name, 'pwd': pwd}
	$.post('/admin/login',postdata,function(data){
		if(0==data.code)
			window.location.href="/admin";
		else
			jsex.dialog.showmsg(data.msg);
	},'json');
}