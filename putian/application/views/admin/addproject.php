<div>
<a class="btn btn-primary" href="<?php if (1 == $atype){echo "/adminarticle/project";}else{echo "/adminarticle/cases";} ?>">返回列表</a>
</div>
<form action="/adminarticle/saveart" method="post" name="project_form" id="project_form">
<div class="span9 project_title">
<label for="project_title">标题:</label><input type="text" id="project_title" name="project_title" class="span9" value="<?php if (isset($title)) echo $title;?>" />
</div>
<div class="span9">
<label for="project_text">内容:</label>
<textarea name="project_text" id="project_text" class="span9"><?php if (isset($content)) echo $content;?></textarea>
<input name="project_id" type="hidden" value="<?php if (isset($id)) echo $id;?>" />
<input name="atype" type="hidden" value="<?php echo $atype;?>" />
</div>
<div class="span9 foot">
<a class="btn btn-primary" onclick="savenews();">发表</a>
</div>
</form>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>    
<script type="text/javascript">
function savenews()
{
	var data = CKEDITOR.instances.project_text.getData();
	var title = $("#project_title").val();
	title = title.replace(/\s+/gm,'');
	data = data.replace(/\s+/gm,'');
	if ('' == title) {
		jsex.dialog.showmsg('案例标题不能为空!');
		return false;
	}
	if('' == data) {
		jsex.dialog.showmsg('案例内容不能为空！');
		return false;
	}else
		$("#project_text").val(data);
	$("#project_form").submit();
}
CKEDITOR.replace( 'project_text',
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
		filebrowserUploadUrl : '/adminnews/uploadimg'
	});
</script>