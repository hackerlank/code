<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> 后台系统</title>
<link href="/css/model.css" rel="stylesheet" type="text/css" />
<link href="/css/fed-std.css" rel="stylesheet" type="text/css" />
<link href="/css/frame.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/js/jquery.js"></script>
<script language="javascript" src="/js/showbox.js"></script>
<style type="text/css">
#imglists li{list-style:none;}
</style>
</head>

<body>
<?php require_once 'header_nav.php';?>
<div class="mainblock">
    <div class="mainwrap">
    	<?php require_once 'left.php';?>
    <div class="m_content">
     <div class="pd_10">
        <?php require_once "{$view}.php";?>
    </div>
    </div>
</div>
<script language="javascript" src="/js/jquery.tmpl.js"></script>
<script language="javascript" src="/js/aside.js"></script>
</body>
</html>
