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
    	<th>内容:</th>
    	<td><textarea name="content" id="content" class="span9"><?php if (isset($info['content'])) echo $info['content'];?></textarea></td>
    </tr>
    <tr>
        <th>
        	<input type="hidden" name="id" value="<?php if (isset($info['id'])) echo $info['id'];?>" />
        	<input type="hidden" name="type" value="<?php echo $type;?>" />
        </th>
        <td><input type="button" onclick="saveart();" value="保 存" class="btn_lv3_1" /></td>
    </tr>
    
</table>
</form>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>    
<script type="text/javascript">
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
