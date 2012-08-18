<div class="tabstyle_1 clearfix">
    <ul>
        <li class="current" onclick="javascript:$(this).addClass('current').siblings().removeClass('current');$('#goodsinfo').show();$('#goodsimgs').hide();"><a href="#">基本信息</a></li>
        <li onclick="javascript:$(this).addClass('current').siblings().removeClass('current');$('#goodsinfo').hide();$('#goodsimgs').show();"><a href="#">图片管理</a></li>
    </ul>
</div>
<div id="goodsinfo">
<form method="post" id="goodsform">
    <table class="bk_form_tbl">
    <tr>
        <th>商品分类</th>
        <td><select name="goodsattr"><option value="0">--请选择--</option><?php echo $attrOption;?><select></td>
    </tr>
    <tr>
        <th>商品名称：</th>
        <td><input type="text" class="input_w198" name='goods_name'   /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>作者：</th>
        <td><input type="text" class="input_w198" name='author_name'   /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>作者分类：</th>
        <td><select name="author_type" class="select_w100" >
        </select></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>职称：</th>
        <td><input type="text" class="input_w198" name="author_title" /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>规格：</th>
        <td><input type="text" class="input_w198" name="standard" /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>工艺：</th>
        <td><select name="craft" class="select_w100" >
        </select></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>题材：</th>
        <td><select name="theme" class="select_w100" >
        </select></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>创作时间：</th>
        <td><select name="age" class="select_w100" >
        </select></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>上架时间：</th>
        <td><input type="text" class="input_w198 datepicker" name="time" /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>价格区间：</th>
        <td><input type="text" class="input_w198" name="price"  /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>简介：</th>
        <td><textarea id='goodsbrief' name="brief" cols="" rows="" class="textarea_w398"></textarea></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <input type="hidden" name='gid' value="<?php echo $gid;?>" />
            <input type="button" onclick="javascript:saveGoods();" value="保 存" class="btn_lv3_1" />
            <input type="reset" value="返 回" class="btn_lv3_2" />
        </td>
    </tr>
    </table>
</form>
</div>
<div id="goodsimgs" style="display:none;">
<div id="imglists">
</div>
<div style="display:block;"><iframe id="goodsimgiframe" src='/admingoods/addimg'></iframe></div>
</div>
<script type="text/javascript" src="/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.datepicker-zh-CN.js"></script>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>    
<link href="/css/redmond/jquery-ui-1.8.10.custom.css" rel="stylesheet" type="text/css" />
<script type='text/javascript'>
$(function(){
     $('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
});
CKEDITOR.replace( 'goodsbrief',
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
function saveGoods()
{
    var postdata = {};
    postdata.goods_name = $("input[name='goods_name']").val();
    postdata.author_name = $("input[name='author_name']").val();
    postdata.author_type = $("select[name='author_type']").val();
    postdata.author_title = $("input[name='author_title']").val();
    postdata.standard = $("input[name='standard']").val();
    postdata.craft = $("select[name='craft']").val();
    postdata.theme = $("select[name='theme']").val();
    postdata.age = $("select[name='age']").val();
    postdata.time = $("input[name='time']").val();
    postdata.price = $("input[name='price']").val();
    postdata.brief = $("#input[name='brief']").val();
    
    if ('' == postdata.goods_name){
        alert('商品名不能为空！');return false;
    }
    var gid = parseInt($("input[name='gid']").val());
    if (gid) postdata.id = gid;
    $.post('/admingoods/savegoods',postdata,function(data){
        alert(data.msg);
        if (0 == data.err && data.gid) {
            $("input[name='gid']").val(data.gid);
            $("#goodsimgiframe").attr('src', '/admingoods/addimg/'+data.gid);
        }
    },'json');
}
function addimg(path)
{
    var str = '<li><a class="close">x</a><a href="javascript:;" onclick="javascript:showimg("'+path+'");"><img src="'+path+'" style="width:200px;height:200px;" /></a></li>';
    $("#imglists").append(str);
}
function showimg(path)
{
    var str = "<img src='"+path+"' />";
    jsex.dialog.showmsgauto(str, '图片预览');
}
$(function(){
    $("select[name='goodsattr']").live('click',function(){
        var aid = parseInt($(this).val());
        $.post('/admingoods/getGoodsAttrInfoLists', {'aid': aid}, function(data){
            var authorTypeOption = '';
            var craftOption = '';
            var themeOption = '';
            var ageOption = '';
            for (var i = 0, iMax = data['author_type'].length; i < iMax; i++)
                authorTypeOption += "<option value='"+data['author_type'][i]['id']+"'>"+data['author_type'][i]['val']+"</option>"; 
            for (var i = 0, iMax = data['craft'].length; i < iMax; i++)
                craftOption += "<option value='"+data['craft'][i]['id']+"'>"+data['craft'][i]['val']+"</option>"; 
            for (var i = 0, iMax = data['theme'].length; i < iMax; i++)
                themeOption += "<option value='"+data['theme'][i]['id']+"'>"+data['theme'][i]['val']+"</option>"; 
            for (var i = 0, iMax = data['age'].length; i < iMax; i++)
                ageOption += "<option value='"+data['age'][i]['id']+"'>"+data['age'][i]['val']+"</option>";
            $("select[name='author_type']").html(authorTypeOption);
            $("select[name='craft']").html(craftOption);
            $("select[name='theme']").html(themeOption);
            $("select[name='age']").html(ageOption);
        }, 'json');
    });
});
</script>
<style type="text/css">
#imglists li{width:220px; height:230px;float:left;}
#imglists li a{width:220px;}
</style>
