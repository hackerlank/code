<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台登陆</title>
<link href="/css/bootstrap.css" rel="stylesheet">
<link href="/css/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/showbox.js"></script>
<script type="text/javascript" src="/js/login.js"></script>
</head>
<body scroll="no">
<div class="FirstDIV"></div>
<div class="SecondDIV">
<div class="login">
    <ul>
    <form method="post" >
        <li><label for="username">用户名：</label><input id="username" name="username" type="text" class="loginput" /></li>
        <li><label for="userpwd">密码：</label><input id="userpwd" name="userpwd" type="password" class="loginput" /></li>
        <li style="display:none;"><label>验证码：</label><input name=""  type="text"  class="authcode" /><span class="codeimg"><img src="../images/authcode.png" /><a href="#" class="changeimg">换一张</a></span></li>
        <li class="last_btn"><input id="loginsub" name="" type="button" class="" value="登陆" /><input name="" type="reset" class="" value="重置" /></li>
    </form>
    </ul>
</div>
</div>
</body>
</html>
