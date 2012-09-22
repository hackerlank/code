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
<a href="/admingoods/goodslist/<?php echo $info['goods_type'];?>" class="btn_lv3_1" style="margin-left:10px;">返回列表</a>
     <div class="pd_10">
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
        <td><input type="text" class="input_w198" name='goods_name' value="<?php echo $info['name'];?>"   /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>作者：</th>
        <td><input type="text" class="input_w198" name='author_name' value="<?php echo $info['author'];?>"   /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>作者分类：</th>
        <td><select name="author_type" class="select_w100" >
        </select></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>职称：</th>
        <td><input type="text" class="input_w198" name="author_title" value="<?php echo $info['author_title'];?>" /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>规格：</th>
        <td><input type="text" class="input_w198" name="standard" value="<?php echo $info['standard'];?>" /></td><td>&nbsp;</td>
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
        <td><input type="text" class="input_w198 datepicker" name="time" value="<?php echo $info['time'];?>" /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>价格区间：</th>
        <td><input type="text" class="input_w198" name="price" value="<?php echo $info['price'];?>"  /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>简介：</th>
        <td><textarea id='goodsbrief' name="brief" cols="" rows="" class="textarea_w398"><?php echo $info['brief'];?></textarea></td><td>&nbsp;</td>
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
<div style="display:block;">
<div id="imglists">
<?php
     echo "<img src='{$info['thumb_img']}' style='width:200px;height:200px;' />";
?>
</div>
<iframe id="goodsimgiframe" src='/admingoods/addimg/<?php echo $gid;?>'></iframe></div>
</div>
</div>
<script type="text/javascript" src="/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.datepicker-zh-CN.js"></script>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>    
<link href="/css/redmond/jquery-ui-1.8.10.custom.css" rel="stylesheet" type="text/css" />
<script type='text/javascript'>
$(function(){
     $('.timepicker').datetimepicker({showSecond: true, dateFormat: 'yy-mm-dd', timeFormat: 'hh:mm:ss'});
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
    postdata.goods_type = $("select[name='goodsattr']").val();
    postdata.brief = CKEDITOR.instances.goodsbrief.getData();
    
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
    var str = '<img src="'+path+'" style="width:200px;height:200px;" /></a>';
    $("#imglists").html(str);
}
function showimg(path)
{
    var str = "<img src='"+path+"' />";
    jsex.dialog.showmsgauto(str, '图片预览');
}
$(function(){
    $("select[name='goodsattr']").live('click',function(){
        var aid = parseInt($(this).val());
        setGoodsAttrOption(aid);
    });

    var gtype = $("select[name='goodsattr']").val();
    if (gtype) setGoodsAttrOption(gtype);
    

});
function setGoodsAttrOption(aid)
{
    var author_type;
    var craft;
    var theme;
    var age;
    <?php
    if ($info) {
    echo "author_type = {$info['author_type']};";
    echo "craft = {$info['craft']};";
    echo "theme = {$info['theme']};";
    echo "age={$info['age']};";
    }
    ?>
    $.post('/admingoods/getGoodsAttrInfoLists', {'aid': aid}, function(data){
        var authorTypeOption = '';
        var craftOption = '';
        var themeOption = '';
        var ageOption = '';
        for (var i = 0, iMax = data['author_type'].length; i < iMax; i++) {
            var selectedStr = '';
            if(data['author_type'][i]['id'] == author_type) selectedStr = "selected='selected'";
            authorTypeOption += "<option value='"+data['author_type'][i]['id']+"'"+selectedStr+">"+data['author_type'][i]['val']+"</option>"; 
        }
        for (var i = 0, iMax = data['craft'].length; i < iMax; i++){
            var selectedStr = '';
            if(data['craft'][i]['id'] == craft) selectedStr = "selected='selected'";
            craftOption += "<option value='"+data['craft'][i]['id']+"'"+selectedStr+">"+data['craft'][i]['val']+"</option>"; 
        }
        for (var i = 0, iMax = data['theme'].length; i < iMax; i++) {
            var selectedStr = '';
            if(data['theme'][i]['id'] == theme) selectedStr = "selected='selected'";
            themeOption += "<option value='"+data['theme'][i]['id']+"'"+selectedStr+">"+data['theme'][i]['val']+"</option>"; 
        }
        for (var i = 0, iMax = data['age'].length; i < iMax; i++) {
            var selectedStr = '';
            if(data['age'][i]['id'] == age) selectedStr = "selected='selected'";
            ageOption += "<option value='"+data['age'][i]['id']+"'>"+data['age'][i]['val']+"</option>";
        }
        $("select[name='author_type']").html(authorTypeOption);
        $("select[name='craft']").html(craftOption);
        $("select[name='theme']").html(themeOption);
        $("select[name='age']").html(ageOption);
    }, 'json');
}
function delimg(id)
{
    var is_sure = confirm("确认删除吗？");
    if (is_sure) {
        $.post('/admingoods/delimg/'+id, '', function(data){
            if (data.err==0) {jsex.dialog.showmsg(data.msg);}
        },'json')
    }
}
</script>
</body>
</html>
