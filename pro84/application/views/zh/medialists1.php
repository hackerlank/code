<!DCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/css/b.css" rel="stylesheet" type="text/css" />
<title>非物质文化遗产</title>
<script language="javascript" src="/js/jquery.js"></script>
</head>

<body>
<div class="head"></div>
<div class="catebox">
    <div class="wp"><h3></h3>
    <ul>
    </ul>
    <p class="back"><a href="javascript:history.go(-1);">返回</a> | <a href="/">首页</a></p></div>
</div>
<div class="wp newslist">
    <ul class="clearfix">
    <?php
        foreach ($lists as $li)
            echo "<li><span><img src='{$li['imgurl']}' style='whidth:204px;height:138px' /></span> <a href='/media/info/{$li['id']}'>{$li['title']}</a></li>";
    ?>
    </ul>
    <div class="page" style="display:none;">
        <a href="#">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a>
    </div>
</div>

</body>
</html>


