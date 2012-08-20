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
<?php
//create option
$optionStr = '';
function GoodsAttrSonOption($data, &$str)
{
    foreach ($data as $v) {
        $prestr = str_repeat('&nbsp;&nbsp;', $v['level']);
        $str .= "<option value='{$v['id']}'>$prestr|--{$v['name']}</option>";
        if ($v['son']) GoodsAttrSonOption($v['son'], $str);
    }
}
if ($attrlist) {
    foreach($attrlist as $attr) {
        $optionStr .= "<option value='{$attr['id']}'>{$attr['name']}</option>";
        if ($attr['son']) GoodsAttrSonOption($attr['son'], $optionStr);
    }
}
//create html
$listStr = '';
function GoodsAttrListStr($data, &$str)
{
    foreach ($data as $v) {
        $prestr = str_repeat('&nbsp;&nbsp;', $v['level']);
        $str .= "<tr><td style='text-align:left;'>$prestr|--<input type='text' value='{$v['name']}' aid='{$v['id']}' class='goodsattrname' /></td><td><a href='javascript:;' onclick='javascript:delGoodsAttr({$v['id']});'>删除</a></td></tr>";
        if ($v['son']) GoodsAttrListStr($v['son'], $str);
    }
}
if ($attrlist) {
    foreach ($attrlist as $attr) {
        $listStr .= "<tr><td style='text-align:left;'><input type='text' value='{$attr['name']}' aid='{$attr['id']}' class='goodsattrname' /></td><td><a href='javascript:;' onclick='javascript:delGoodsAttr({$attr['id']});'>删除</a></td></tr>";
        if ($attr['son']) GoodsAttrListStr($attr['son'], $listStr);
    }
}
?>
     <div class="pd_10">
<div>
    <h2>商品分类管理</h2>
    <p class="page_info">对商品分类的添加，删除，编辑</p>
</div>
<div class="s_box">
    <label>请选择父分类:</label>
    <select name="pid"><option value='0'>顶级分类</option><?php echo $optionStr;?></select>
    <label>分类名：</label>
    <input type="text" name="typename" value="" />
    <input type="button" class="btn_lv4_1" onclick="javascript:saveGoodsAttr();" value="添加" />
</div>
<table class="datelist-1" style="width:30%;">
<tbody>
<?php echo $listStr;?>
</tbody>
</table>
</div>
<script type="text/javascript">
function saveGoodsAttr()
{
    var pid = parseInt($("select[name='pid']").val());
    var name = $("input[name='typename']").val();
    $.post('/admingoods/addattr', {'pid': pid, 'name': name}, function(data){
        alert(data.msg, '温馨提示');
        if (0 == data.err) {
            $("input[name='typename']").val('');
            window.location.reload();
        }
    }, 'json');
}
function delGoodsAttr(id)
{
    var sure = confirm("确定要删除吗？");
    if(!sure) return false;

    if (id) {
        $.post('/admingoods/delattr', {'id': id}, function(data){
            alert(data.msg, '温馨提示');
            if (0 == data.err) window.location.reload();
        }, 'json');
    } else {
        alert("请指定要删除的商品分类");
    }
}
$(function(){
    $('.goodsattrname').live('change',function(){
        var id = $(this).attr('aid');
        var name = $(this).val();
        name = name.replace(/\s+/gm, '');
        if ('' == name) {alert('商品分类名不能为空');return false;}
        if (id && name) {
            $.post('/admingoods/updateattr', {'id': id, 'name': name}, function(data){
                alert(data.msg);
                if (0 == data.err) setTimeout('window.location.reload();',300);
            }, 'json');
        }
    });
});
</script>
</body>
</html>
