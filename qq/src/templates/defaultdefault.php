<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="http://test.qq.com/" />
<script src="<?php echo TMConfig::get('base_url');?>js/jslib-1.4.2.js" type="text/javascript"></script>
<?php include ROOT_PATH . 'templates/scripts.php'; ?>
</head>
<body>
<?php if (TMAuthUtils::isLogin()):?>
<?php echo TMAuthUtils::getUin();?>
&nbsp;&nbsp;<a href="javascript:;" onclick="$app.auth.logout();">退出</a> <br />
<?php else:?>
<a href="javascript:;" onclick="$app.auth.login();">login</a><br />
<?php endif;?>
<!-- 检查是否为会员 -->
<a href="javascript:;" onclick="isclub();" >我是会员吗？</a><br />
<script type="text/javascript">
function isclub(){
	if (!$app.auth.isLogin()) {
		$app.auth.login();return true;
	}
	stuffContent = 	function (data) {
						if (1 == data[0]['is_club']){
							alert("你是QQ会员");
						} else {
							alert("你不是QQ会员");
						}
					}
	$.getScript('http://vipfunc.qq.com/common/user.php?callback=stuffContent&data='+$app.auth.getQQNum()+',is_club',function(){});
}
</script>
<!-- 邀请微博好友 -->
<script data="{&quot;desc&quot;:&quot;  《艾尔之光》QQ大礼包 来就送Q秀Q钻&quot;,&quot;acturl&quot;:&quot;http://games.qq.com/gameact/tx3.htm&quot;,&quot;maxfriend&quot;:&quot;5&quot;}" src="http://t.act.qq.com/miniblogjs/mini.blog.friends.1.6_len.js" type="text/javascript" id="iframe"></script>
<a href="javascript:;" onclick="javascript:inviteTFriend();">邀请微博好友</a><br />
<script type="text/javascript">
//邀请微博好友
function inviteTFriend(){
	if($app.auth.isLogin()){
		var login = $app.auth.getQQNum();
		var url = 'http://games.qq.com/gameact/tx3.htm?fromuser='+"641009392"+login+"293900146";
		blog_friend.showFriend(url);
	} else {
		$app.auth.login({'callback':function(uin){}});
	}
} 
</script>
<!-- 表单提交控制  -->
<form id="myTestForm" action="/default/testform" method="post">
<input type="text" name="testname" value="" />
<input type="submit" id="subForm" value="提交" />
</form>
</body>
</html>