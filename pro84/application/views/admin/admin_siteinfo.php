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
    <table class="bk_form_tbl">
        <tr>
            <th>联系QQ：</th>
            <td><input type="text" name="qq" value="" class="siteinfo" /></td>
        </tr>

    </table>
    
</div>
</div>
<script type="text/javascript">
$('.siteinfo').live('change', function(){
	var flag = $(this).attr('name');
	var val = $(this).val();
});
</script>
</body>
</html>
