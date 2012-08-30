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
    <h2>商品列表</h2>
    <p class="page_info">商品列表管理</p>
</div>
<div class='s_box'>
<form method="post">
<label>请选择分类：</label>
<select name='gtype'><option>--请选择--</option><?php echo $attrOption;?></select>
<input type="submit" class="btn_lv3_1" value="确定" />
</form>
</div>
<?php if($goodsList):?>
<table class="datelist-1" style="width:100%;">
<thead>
    <tr>
        <th>商品名</th>
        <th>作者</th>
        <th>工艺</th>
        <th>题材</th>
        <th>创作时间</th>
        <th>操作</th>
    </tr>
</thead>
<tbody>
<?php
    foreach ($goodsList as $goods)
        echo "<tr><td>{$goods['name']}</td>".
             "<td>{$goods['author']}</td>".
             "<td>{$goods['craft']}</td>".
             "<td>{$goods['theme']}</td>".
             "<td>{$goods['time']}</td>".
             "<td><a href='/admingoods/add/{$goods['id']}'>编辑</a> | 删除</td></tr>";
?>
</tbody>
</table>
<?php endif;?>
</div>
</body>
</html>
