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
<form method="get">
<label>请选择分类：</label>
 <select name='gtype' id='gtype'><option value='0'>--请选择--</option><?php echo $attrOption;?></select>
<input id='gotolists' type="button" class="btn_lv3_1" value="确定" />
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
<tbody id="goodslist">
<?php
    foreach ($goodsList as $goods)
        echo "<tr id='good_{$goods['id']}'><td>{$goods['name']}</td>".
             "<td>{$goods['author']}</td>".
             "<td>{$goods['craft']}</td>".
             "<td>{$goods['theme']}</td>".
             "<td>{$goods['time']}</td>".
             "<td><a href='javascript:;' onclick='javascript:showimg(\"{$goods['thumb_img']}\");'>图片预览</a> | <a href='/admingoods/add/{$goods['id']}'>编辑</a> | <a href='javascript:;' onclick='javascript:delGoods({$goods['id']});'>删除</a></td></tr>";
?>
</tbody>
</table>
<?php echo $pagination;?>
<?php endif;?>
</div>
<script type="text/javascript">
$("#goodslist tr:even").addClass("eq");
$("#goodslist tr").live('mouseover', function(){$(this).addClass('over');});
$("#goodslist tr").live('mouseout', function(){$(this).removeClass('over');});
function showimg(path)
{
    var str = "<img src='"+path+"' />";
    jsex.dialog.showmsgauto(str, '图片预览');
}
function delGoods(id)
{
    var is_sure = confirm("确定删除吗？");
    if (is_sure) {
        $.post('/admingoods/delgoods/'+id,'',function(data){
            if (data.err) alert(data.msg);
            else $('#good_'+id).remove();
        },'json'); 
    }
}
$("#gotolists").click(function(){
    var gtype = $('#gtype').val();
    if (gtype) window.location.href= "/admingoods/goodslist/"+gtype;
});
</script>
</body>
</html>
