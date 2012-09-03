     <div>
    	<h2><?php echo $title;?></h2>
        <p class="page_info">对<?php echo $title;?>管理，编辑等</p>
     </div>
     <table class="bk_form_tbl">
<?php if($page == 'indeximg'): ?>
     <tr>
     	<th>图片：</th>
    	<td>
        	<input type="hidden" name="newsimg" value="<?php echo $list[0]['imgurl'];?>" />
        	<div <?php if(empty($list[0]['imgurl'])){echo "style='display:none;'";} ?> id="newsimgdiv">
            <img src="<?php echo $list[0]['imgurl'];?>" id="newsimg" style="width:200px;height:80px;float:left;" />
            <a href="javascript:;" class="btn" onclick="javascript:$('input[name=newsimg]').val('');$('#newsimgdiv').hide();">删除</a>
            </div>
            <div style="height:30px;float:left;">
            <iframe style="float:left;" src="/admin/upload/addNewImg"  frameborder="0"  height="100%" width="100%" scrolling="auto" allowtransparency="true"></iframe>
            </div>
    	</td>
     </tr>
     <tr>
     	<th>图片：</th>
    	<td>
        	<input type="hidden" name="newsimg2" value="<?php echo $list[1]['imgurl'];?>" />
        	<div <?php if(empty($list[1]['imgurl'])){echo "style='display:none;'";} ?> id="newsimgdiv2">
            <img src="<?php echo $list[1]['imgurl'];?>" id="newsimg2" style="width:200px;height:80px;float:left;" />
            <a href="javascript:;" class="btn" onclick="javascript:$('input[name=newsimg2]').val('');$('#newsimgdiv').hide();">删除</a>
            </div>
            <div style="height:30px;float:left;">
            <iframe style="float:left;" src="/admin/upload/addNewImg2"  frameborder="0"  height="100%" width="100%" scrolling="auto" allowtransparency="true"></iframe>
            </div>
    	</td>
     </tr>
<?php else: ?>
     <?php if (isset($imgurl)):?>
     <tr>
     	<th>图片：</th>
    	<td>
        	<input type="hidden" name="newsimg" value="<?php echo $imgurl;?>" />
        	<div <?php if(empty($imgurl)){echo "style='display:none;'";} ?> id="newsimgdiv">
            <img src="<?php echo $imgurl;?>" id="newsimg" style="width:200px;height:80px;float:left;" />
            <a href="javascript:;" class="btn" onclick="javascript:$('input[name=newsimg]').val('');$('#newsimgdiv').hide();">删除</a>
            </div>
            <div style="height:30px;float:left;">
            <iframe style="float:left;" src="/admin/upload/addNewImg"  frameborder="0"  height="100%" width="100%" scrolling="auto" allowtransparency="true"></iframe>
            </div>
    	</td>
     </tr>
     <?php endif;?>
    <?php if($id==4):?>
        <tr>
            <th>首页内容：</th>
            <td><textarea class='order_info_textarea_2' name="article_desc" id="article_desc"><?php echo $descs;?></textarea></td>
        </tr>
    <?php endif;?>
     <tr>
     <th></th>
     <td>
     	<textarea name="article_text" id="news_text" class="span9"><?php if (isset($content)) echo $content;?></textarea>
     	<input type="hidden" name='article_id' id="article_id"  value="<?php if (isset($id)) echo $id;?>" />
     	<input type="button" value="保存" onclick="javascript:savenews();" class="btn_lv4_1" style="margin-top:20px;margin-left:20px;"/>
     </td>
     </tr>
     </table>
<?php endif;?>     
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>   
<script type="text/javascript">
function savenews()
{
	var content = CKEDITOR.instances.news_text.getData();
	var id = $("#article_id").val();
	var imgurl = $("input[name=newsimg]").val();
    var descs = $('#article_desc').val() || '';
	if('' == content) {
		jsex.dialog.showmsg('新闻内容不能为空！');
		return false;
	}
	var postdata = {'id': id, 'content': content,'imgurl':imgurl,'descs':descs}
	$.post("/adminpage/save",postdata,function(data){
		jsex.dialog.showmsg(data.msg);
	},'json');
}
<?php if($page != 'indeximg'):?>
CKEDITOR.replace( 'news_text',
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
<?php endif;?>
//添加图片
function addNewImg(src)
{
	$("input[name=newsimg]").val(src);
    $("#newsimgdiv img").attr('src',src);
    $("#newsimgdiv").show();
    $.post('/adminpage/saveIndeximg',{'id': 6,'imgurl': src});
}
function addNewImg2(src)
{
	$("input[name=newsimg2]").val(src);
    $("#newsimgdiv2 img").attr('src',src);
    $("#newsimgdiv2").show();
    $.post('/adminpage/saveIndeximg',{'id': 7,'imgurl': src});
}
$(function(){
	<?php 
	if(1 == $id)
		echo  '$(".fed-menu-list li").removeClass("current").eq(6).addClass("current");';
    elseif (2 == $id)
		echo  '$(".fed-menu-list li").removeClass("current").eq(7).addClass("current");';
	elseif (3 == $id)
		echo  '$(".fed-menu-list li").removeClass("current").eq(9).addClass("current");';
	elseif (4 == $id)
		echo  '$(".fed-menu-list li").removeClass("current").eq(10).addClass("current");';
	elseif (5 == $id)
		echo  '$(".fed-menu-list li").removeClass("current").eq(8).addClass("current");';	
	?>
});
</script>
</body>
</html>
