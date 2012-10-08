<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?php echo TMConfig::get('base_url'); ?>" />
<title>您访问的页面不存在</title>
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
<!-- <?php echo empty($errorMsg) ? '' : $errorMsg; ?> -->
<div class="pagebody">
  <div class="infoContent">您访问的页面不存在，<a href="<?php echo TMConfig::get('base_url'); ?>">返回活动首页</a></div>
</div>
</body>
</html>