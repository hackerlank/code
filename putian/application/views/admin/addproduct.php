<div>
	<h2>产品编辑</h2>
    <p class="page_info">对产品进行编辑</p>
</div>
<form method="post" action="/adminproduct/saveproduct" id="productform" >
<table class="bk_form_tbl">
	<tr>
		<th>产品分类:</th>
		<td>
    		<?php
            $str = '';
            function createstype($data, &$str,&$total,$id)
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
                            createstype($v['son'], $str,$total,$id);
                    }
                }
                    
            }
            foreach ($ptype as $row){
                $total = 1;
                if ($info['type']==$row['id']) 
                    $selected = " selected='selected'";
                else 
                    $selected = '';
                $str .= "<option $selected value='{$row['id']}'>{$row['typename']}</option>";
                if (!empty($row['son']))
                    createstype($row['son'],$str, $total,$info['type']);
            } 
            ?>
        <select id="typepid" name="type"><?php echo $str;?></select>
		</td>
	</tr>
		<tr>
		<th>产品名:</th>
		<td><input type="text" name="proname" id="proname" value="<?php if (isset($info)) echo $info['proname'];?>" /></td>
	</tr>
		<tr>
		<th>产品简介:</th>
		<td><textarea name="prodesc" id="prodesc" class="span9"><?php if (isset($info)) echo $info['prodesc'];?></textarea></td>
	</tr>
		<tr>
		<th>产品特点:</th>
		<td><textarea name="proargv" id="proargv" class="span9"><?php if (isset($info)) echo $info['proargv'];?></textarea></td>
	</tr>
		<tr>
		<th>详细参数:</th>
		<td><textarea name="proinfo" id="proinfo" class="span9"><?php if (isset($info)) echo $info['proinfo'];?></textarea></td>
	</tr>
		<tr>
		<th>应用范围:</th>
		<td><textarea name="proarea" id="proarea" class="span9"><?php if (isset($info)) echo $info['proarea'];?></textarea></td>
	</tr>
	<tr>
		<th>相关下载:</th>
		<td><textarea name="prodown" id="prodown" class="span9"><?php if (isset($info)) echo $info['prodown'];?></textarea></td>
	</tr>
	<tr>
		<th>产品图片:</th>
		<td>
    		<input type="hidden" name="proimg" value="<?php if ($info['proimg']) echo $info['proimg'];?>" />
            <div <?php if(empty($info['proimg'])){$imgurl = '';echo "style='display:none;'";} ?> id="porimgdiv">
            <img src="<?php echo $info['proimg'];?>" id="proimg" style="width:200px;height:80px;float:left;" />
            <a href="javascript:;" class="btn" onclick="javascript:$('input[name=proimg]').val('');$('#porimgdiv').hide();">删除</a>
            </div>
            <div style="height:30px;float:left;">
            <iframe style="float:left;" src="/admin/upload/proimg"  frameborder="0"  height="100%" width="100%" scrolling="auto" allowtransparency="true"></iframe>
            </div>
		</td>
	</tr>
	<tr>
		<th></th>
		<td>
		<input type="hidden" name="pid" value="<?php if (isset($info)) echo $info['id']?>" />
		<input type="button" value="保存" onclick="javascript:savepro();" class="btn_lv4_1"  />
		</td>
	</tr>
</table>

</form>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>    
<script type="text/javascript">
CKEDITOR.replace( 'prodesc',
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
CKEDITOR.replace( 'proargv',
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
CKEDITOR.replace( 'proinfo',
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
CKEDITOR.replace( 'proarea',
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
CKEDITOR.replace( 'prodown',
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
//ajax提交图片
$(function(){
	//
	$("#proimg").live('change',function(){
		$("#proimg_form").submit();
	});
	//删除图片
	$("#imglists .close").live('click',function(){
		$(this).parent().remove();
	});
});
function proimg(src)
{
	$("input[name=proimg]").val(src);
    $("#porimgdiv img").attr('src',src);
    $("#porimgdiv").show();
}
function savepro()
{
	var proname = $("#proname").val();
	if(''==proname) {
		alert("标题不能为空");
		return false;
	}
	var ptype = $("#typepid").val();
	if(!ptype) {
		alert("请选择分类");
		return false;
	}
	$("#productform").submit();
}
</script>
