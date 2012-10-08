<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>普天</title>
<link rel="stylesheet" type="text/css" href="/css/main.css"/>
<script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
</head>

<body>
<div class="header wp">
	<a href="#" class="logo"><img src="/images/logo.jpg" /></a> <a href="#">加入收藏</a> <a href="#" class="language">中文</a> | <a href="#" class="language">English</a> <a href="#" class="sub-logo"><img src="/images/sub-logo.jpg" /></a>
</div>
<div class="wp">
 <ul class="nav">
 	<li><a href="/">首 页</a><span>|</span></li>
    <li id="nav1"><a href="#">关于普天</a><span>|</span>
    	<dl id="nav_1" style="display:none;">
        	<dd><a href="/page/aboutus">公司简介</a></dd>
            <dd><a href="/page/honour">资质荣誉</a></dd>
            <dd><a href="/page/slogen">企业文化</a></dd>
        </dl>
    </li>
    <li id="nav2"><a href="/media/lists">新闻资讯</a><span>|</span>
    	<dl id="nav_2" style="display:none;">
    	<?php
    	    $list = '';
        	$list = $this->Articles->GetTypelists("WHERE type=1 and pid=0");
        	$str = '';
        	foreach ($list as $row) {
        	    $str .= "<dd><a href='/media/lists/{$row['id']}'>{$row['typename']}</a></dd>";
        	}
        	echo $str;
    	?>
        </dl>
    </li>
    <li id="nav3"><a href="/products/lists">产品与服务</a><span>|</span>
    	<dl id="nav_3" style="display:none;">
    	<?php
    	    $list = $this->Product->GetTypeList("WHERE pid=0");
    	    $str = '';
    	    foreach ($list as $row) {
    	        $str .= "<dd><a href='/products/lists/{$row['id']}'>{$row['typename']}</a></dd>";
    	    }
    	    echo $str;
    	?>
        </dl>
    </li>
    <li id="nav4"><a href="/media/cases">解决方案</a><span>|</span>
    	<dl id="nav_4" style="display:none;">
    	<?php
    	$list = '';
    	$list = $this->Articles->GetTypelists("WHERE type=2 and pid=0");
    	$str = '';
    	foreach ($list as $row) {
    	    $str .= "<dd><a href='/media/cases/{$row['id']}'>{$row['typename']}</a></dd>";
    	}
    	echo $str;
    	?>
        </dl>
    </li>
    <li><a href="/page/example">成功案例</a><span>|</span></li>
    <li><a href="/page/link">联系我们</a><span></span></li>
    </ul>
</div>
<div class="wp banner"><img src="/images/banner.jpg" /></div>
<script type="text/javascript">
$(function(){
	$("#nav1").mouseover(function(){$("#nav_1").show();}).mouseout(function(){$("#nav_1").hide();});
	$("#nav2").mouseover(function(){$("#nav_2").show();}).mouseout(function(){$("#nav_2").hide();});
	$("#nav3").mouseover(function(){$("#nav_3").show();}).mouseout(function(){$("#nav_3").hide();});
	$("#nav4").mouseover(function(){$("#nav_4").show();}).mouseout(function(){$("#nav_4").hide();});
});
</script>
