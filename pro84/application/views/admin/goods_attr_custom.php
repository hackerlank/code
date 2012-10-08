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
    <h2>自定义商品属性</h2>
    <p class="page_info">对商品属性的添加，删除，编辑</p>
</div>
<form method="post">
<div class="s_box">
    <label>商品分类:</label>
    <select name="gtype"><option value='0'>--请选择商品分类--</option><?php echo $optionStr;?></select>
    <select name="attrflag"><option value='0'>--请选择商品属性--</option>
    <?php 
    	foreach($attrlists as $v) {
    		if ($attrflag == $v['flag'])
    			echo "<option value='{$v['flag']}' selected='selected'>{$v['val']}</option>";
    		else 
    			echo "<option value='{$v['flag']}'>{$v['val']}</option>";
    	}
    ?>
    </select>
    <input onclick="javascript:goToAttrLists();" type="button" class="btn_lv3_1" value="确定" />
</div>
</form>

<?php if (isset($goodsattr)):?>
<div class="s_box">
<lable>商品属性：</lable>
<input type="text" name="goodsattr" value="" />
<input type="button" value="添加" class="btn_lv3_1" onclick="javascript:addGoodsAttr();" />
<table class="datelist-1" style="width:30%;" id="attrlists">
<?php
foreach ($goodsattr as $v)
	echo "<tr><td><input type='text' goodsattrid='{$v['id']}' value='{$v['val']}' class='goodsattrname' /></td><td><a href='javascript:;' goodsattrid='{$v['id']}' class='delgoodsattr'>删除</a></td></tr>"; 
?>
</table>
</div>
<?php endif;?>
<script type="text/javascript">
function goToAttrLists()
{
	var gtype = parseInt($("select[name='gtype']").val());
	var attrflag = $("select[name='attrflag']").val();
	
	if (!gtype) {
		alert("请选择商品分类");
		return false;
	}
	if (attrflag == '0') {
		alert("请选择商品属性");
		return false;
	}
	window.location.href="/admingoods/attrcustom/"+gtype+"/"+attrflag;
}
function addGoodsAttr()
{
	var aid = parseInt($('select[name="gtype"]').val());
	var atype = $('select[name="attrflag"]').val();
	var val = $('input[name="goodsattr"]').val();

	if(val == '') {
		alert("请填写商品属性值");
		return false;
	}

	$.post('/admingoods/addAttrInfo', {'aid':aid, 'attrinfo': val, 'atype':atype}, function(data){
		if (data['err'] == 0){
			var str = "<tr><td><input type='text' goodsattrid='"+data['id']+"' value='"+val+"' class='goodsattrname' /></td><td><a href='javascript:;' goodsattrid='"+data['id']+"' class='delgoodsattr'>删除</a></td></tr>";
			$('#attrlists').append(str); 
		}
	}, 'json');
}

$(function(){
	$('.delgoodsattr').live('click', function(){
		var is_sure = confirm("确定要删除吗？");
		if (is_sure) {
			var doc_obj = $(this);
			var goodsattrid = parseInt($(this).attr('goodsattrid'));
			$.post('/admingoods/delAttrInfo', {'id': goodsattrid}, function(data){
				if(data['err']==0) doc_obj.parent().parent().remvoe();
				else aler(data['msg']);
			}, 'json');
		}
	});

	$('.goodsattrname').live('change', function(){
		var id = parseInt($(this).attr('goodsattrid'));
		var attrinfo = $(this).val();
		if (attrinfo == '') {
			alert("属性值不能为空");
			return false;
		}
		$.post('/admingoods/updateAttrInfo', {'id':id, 'attrinfo':attrinfo});
	});
});
</script>
</body>
</html>
