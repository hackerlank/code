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
<div>
	<h2><?php echo $title;?>编辑</h2>
    <p class="page_info">对<?php echo $title;?>进行编辑</p>
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
        <?php
            $str = '';
            function createstype($data, &$str,$id)
            {
                foreach ($data as $k=>$v) {
                    if(!empty($v)) {
                        $prestr = str_repeat('&nbsp;&nbsp;',$v['listid']);
                        if ($id==$v['id']) 
                            $selected = " selected='selected'";
                        else 
                            $selected = '';
                        $str .= "<option $selected value='{$v['id']}'>$prestr|--{$v['typename']}</option>";
                        if (!empty($v['son']))
                            createstype($v['son'], $str,$total,$id,$pid);
                    }
                }
                    
            }
            foreach ($typelist as $row){
                if ($id==$row['id']) 
                    $selected = " selected='selected'";
                else 
                    $selected = '';
                $str .= "<option $selected value='{$row['id']}'>{$row['typename']}</option>";
                if (!empty($row['son']))
                    createstype($row['son'],$str,$info['id']);
            } 
        ?>
        <select id="typepid" name="atype"><?php echo $str;?></select>
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
        <iframe style="float:left;" src="/admin/upload/addNewImg"  frameborder="0"  height="100%" width="100%" scrolling="auto" allowtransparency="true"></iframe>
        </div>
    </td>
    </tr>
    <tr>
        <th>时间：</th>
        <td><input type="text" name="date" value="<?php echo $info['date'];?>" class="datepicker" /></td>
    </tr>
    <tr>
    	<th>内容:</th>
    	<td><textarea name="content" id="content" class="span9"><?php if (isset($info['content'])) echo $info['content'];?></textarea></td>
    </tr>
    <tr class="medialists2" style="display:none;"><th>展览时间</th><td><input type='text' name='show_date' class='datepicker' value='<?php echo $info['show_time'];?>' /></td></tr>
    <tr class="medialists2" style="display:none;"><th>展览地点</th><td><input type="text" name='show_area' value="<?php echo $info['show_area'];?>" /></td></tr>
    <tr class="medialists2" style="display:none;"><th>展览链接</th><td><textarea name="show_link" style="width:500px;"><?php echo $info['show_link'];?></textarea></td></tr>
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
<script type="text/javascript">
$(function(){
     $('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
    $('#typepid').live('change',function(){
        var typepid = $(this).val();
        if (typepid) {
            $.post('/adminarticle/gettypeinfo',{'typeid':typepid}, function(data){
                if(data['info']['template'] == 'medialists2') $('.medialists2').show();
                else $('.medialists2').hide();
            },'json');
        }
    });
});
function saveart()
{
	var data = CKEDITOR.instances.content.getData();
	var title = $("#title").val();
	title = title.replace(/\s+/gm,'');
	data = data.replace(/\s+/gm,'');
	if ('' == title) {
		jsex.dialog.showmsg('新闻标题不能为空!');
		return false;
	}
	if('' == data) {
		jsex.dialog.showmsg('新闻内容不能为空！');
		return false;
	}else
		$("#content").val(data);
	$("#artform").submit();
}
CKEDITOR.replace( 'content',
	{
		extraPlugins : 'abbr',
		toolbar :
		[
			[ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ],
			[ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ],
			[ 'Link','Unlink' ],
			[ 'Image','Table','HorizontalRule','SpecialChar','PageBreak' ],
			[ 'Styles','Format','Font','FontSize' ],
			[ 'TextColor','BGColor' ],
			[ 'Maximize', 'ShowBlocks','-','About' ]
		],
		filebrowserUploadUrl : '/admin/uploadimg'
	});
//添加图片
function addNewImg(src)
{
	$("input[name=newsimg]").val(src);
    $("#newsimgdiv img").attr('src',src);
    $("#newsimgdiv").show();
}
</script>
</body></html>
