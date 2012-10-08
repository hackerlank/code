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
	<h2><?php echo $title;?>编辑</h2>
    <p class="page_info">对<?php echo $title;?>进行编辑.备注：排序时按照新闻发布时间先后，时间可以自己选择，默认去当前时间。</p>
</div>
<form action="/adminarticle/saveart" method="post" id="artform" name="artform">
<table class="bk_form_tbl">
    <tr>
        <th>标题:</th>
        <td><input type="text" id="title" name="title"  value="<?php if (isset($info['title'])) echo $info['title'];?>" /></td>
    </tr>
    <tr>
        <th>类别:</th>
        <td>
        <select id="typepid" name="atype"><?php echo $type_option;?></select>
        </td>
    </tr>
    <tr>
    <th>首页图片：</th>
    <td>
    	<input type="hidden" name="newsimg" value="<?php echo $info['imgurl'];?>" />
    	<div <?php if(empty($info['imgurl'])){echo "style='display:none;'";} ?> id="newsimgdiv">
        <img src="<?php echo $info['imgurl'];?>" id="newsimg" style="width:200px;height:80px;float:left;" />
        <a href="javascript:;" class="btn" onclick="javascript:$('input[name=newsimg]').val('');$('#newsimgdiv').hide();">删除</a>
        </div>
        <div style="height:30px;float:left;">
        <iframe style="float:left;" src="/admin/upload/addNewImg"  frameborder="0"  height="100%" width="100%" scrolling="no" allowtransparency="true"></iframe>
        </div>
    </td>
    </tr>
    <tr>
        <th>发布时间：</th>
        <td><input type="text" name="time" value="<?php echo $info['time'];?>" class="timepicker" /><span style="color:red;">前台显示顺序可通过此时间调整</span></td>
    </tr>
    <tr>
    	<th>内容:</th>
    	<td><textarea name="content" id="content" class="span9"><?php if (isset($info['content'])) echo $info['content'];?></textarea></td>
    </tr>
    <tr><th>简介:</th><td><textarea id="description" name="description" style="width:400px;height:200px;"><?php echo $info['description'];?></textarea></td></tr>
    <tr>
        <th>
        	<input type="hidden" name="id" value="<?php if (isset($info['id'])) echo $info['id'];?>" />
        	<input type="hidden" name="type" value="<?php echo $type;?>" />
        </th>
        <td><input type="button" onclick="saveart();" value="保 存" class="btn_lv3_1" /></td>
    </tr>
    
</table>
</form>
<script type="text/javascript" src="/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.datepicker-zh-CN.js"></script>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>    
<link href="/css/redmond/jquery-ui-1.8.10.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="/js/jquery-ui-timepicker-zh-CN.js"></script>
<script type="text/javascript">
$(function(){
    $('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
	$('.timepicker').datetimepicker({showSecond: true, dateFormat: 'yy-mm-dd', timeFormat: 'hh:mm:ss'});
});

function saveart()
{
	var content = CKEDITOR.instances.content.getData();
	var description = CKEDITOR.instances.description.getData();
	var title = $("#title").val();
	title = title.replace(/\s+/gm,'');
	content = content.replace(/\s+/gm,'');
	if ('' == title) {
		jsex.dialog.showmsg('新闻标题不能为空!');
		return false;
	}
	if('' == content) {
		jsex.dialog.showmsg('新闻内容不能为空！');
		return false;
	}else
		$("#content").val(content);
	
	$("#description").val(description);
	$("#artform").submit();
}

CKEDITOR.replace('content', {filebrowserUploadUrl: '/admin/uploadimg'});
CKEDITOR.replace('description', {filebrowserUploadUrl: '/admin/uploadimg'});

//添加图片
function addNewImg(src)
{
	$("input[name=newsimg]").val(src);
    $("#newsimgdiv img").attr('src',src);
    $("#newsimgdiv").show();
}
</script>
</div>
</body></html>
