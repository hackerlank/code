<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> 后台系统</title>
<link href="/css/model.css" rel="stylesheet" type="text/css" />
<link href="/css/fed-std.css" rel="stylesheet" type="text/css" />
<link href="/css/frame.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/js/jquery.js"></script>
<script language="javascript" src="/js/showbox.js"></script>
</head>
<body>
     <div class="pd_10">
<div>
    <form method="post" id="userpwd">
    <table class="bk_form_tbl">
        <tr>
            <th>原密码：</th>
            <td><input type="password" name="oldpwd" value="" /></td>
            <td>&nbsp;&nbsp;输入原始密码(必填)</td>
        </tr>
        <tr>
            <th>新密码：</th>
            <td><input type="password" name="newpwd" value="" /></td>
            <td>&nbsp;&nbsp;输入你的新密码(必填)</td>
        </tr>
        <tr>
            <th>新密码：</th>
            <td><input type="password" name="repwd" value="" /></td>
            <td>&nbsp;&nbsp;再次输入你的新密码(必填)</td>
        </tr>
<?php
    if (isset($errmsg))
        echo "<tr><td></td><td>$errmsg</td></tr>";
?>
        <tr>
            <td></td>
            <td><input type="button" value="保 存" class="btn_lv3_1" onclick="javascript:changepwd();" /></td>
        </tr>
    </table>
    </form>
</div>
</div>
<script type="text/javascript">
function changepwd()
{
    var oldpwd = $("input[name='oldpwd']").val();
    var newpwd = $("input[name='newpwd']").val();
    var repwd  = $("input[name='repwd']").val();
    
    if ('' == oldpwd || '' == newpwd || '' == repwd) {
        alert('请提交完整信息');
        return false;
    }
    if (newpwd != repwd) {
        alert("两次输入的新密码不一致");
        return false;
    }
    $("#userpwd").submit();
}
</script>
</body>
</html>
