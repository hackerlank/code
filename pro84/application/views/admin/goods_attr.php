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
    <h2>商品属性</h2>
    <p class="page_info">对商品属性的添加，删除，编辑</p>
</div>

<div class="s_box">
<label>商品属性：</label>
<input type="text" name="goodsattr" value="" />
<input type="button" onclick="javascript:addGoodsAttr();" class="btn_lv3_1" value="添加" />
</div>

<table class="datelist-1" style="width:30%;" id="attrlists">
<?php
$str = '';
if ($attrlists)
	foreach($attrlists as $v)
		$str .= "<tr><td><input type='text' attrid='{$v['id']}' class='goodsattr' value='{$v['val']}' /></td>".
			"<td><a href='javascript:;' attrid='{$v['id']}' class='delattr'>删除</a></td></tr>";
echo $str;
?>
<tbody>

</tbody>
</table>
</div>
<script type="text/javascript">
function addGoodsAttr()
{
	var attrname = $("input[name='goodsattr']").val();
	if (attrname != '') {
		$.post('/admingoods/addgoodsattr', {'attrname':attrname}, function(data){
			if (data['err']) {
				alert(data['msg']);
				return false;
			}
			var str = "<tr><td><input type='text' attrid='"+data['attrid']+"' class='goodsattr' value='"+attrname+"' /></td>"+
						"<td><a href='javascript:;' attrid='"+data['attrid']+"' class='delattr'>删除</a></td></tr>";
			$('#attrlists').append(str);
		}, 'json');
	}
}
function delAttr(id)
{
	
}
$(function(){
	$('.goodsattr').live('change', function(){
		var attrname = $(this).val();
		var attrid = parseInt($(this).attr('attrid'));
		$.post('/admingoods/updategoodsattr', {'id':attrid, 'val':attrname});
	});

	$('.delattr').live('click', function(){
		var doc_obj = $(this);
		var id = parseInt($(this).attr('attrid'));
		var is_sure = confirm("确定删除该属性");
		if (is_sure && id) {
			$.post('/admingoods/delgoodsattr', {'id': id}, function(data){
				if(data['err']) {alert(data['msg']);return false;}
				doc_obj.parent().parent().remove();
			}, 'json');
		}
	});
});
</script>
</body>
</html>
