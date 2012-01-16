<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?php echo TMConfig::get('base_url'); ?>" />
<title>系统繁忙</title>
<style type="text/css">
<!--
body {font-family:Arial,Helvetica,sans-serif normal;font-size:12px;background-color:#666666;margin-top:30px;}
a:link, a:visited {color:#560900;text-decoration:none;}
a:hover {color:#560900;text-decoration:underline;}
.pagebody{width:906px;height:400px;margin:0px auto;color:#000000;}
.infoContent{height:50px;margin:200px 0px;text-align:center;}
-->
</style>
</head>
<body>
<?php if (!empty($autoRidrect)) { ?>
<script type="text/javascript">
window.setTimeout(function(){
    location.href = '<?php echo TMConfig::get('base_url'); ?>';
},5000)
</script>
<?php } ?>
<div class="pagebody">
  <div class="infoContent"><?php echo empty($errorMsg) ? '系统繁忙' : $errorMsg; ?>，<a href="<?php echo TMConfig::get('base_url'); ?>">返回活动首页</a></div>
</div>
</body>
</html>