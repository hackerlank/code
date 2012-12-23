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
    <table class="bk_form_tbl" id="goodsinfotable">
    <tr>
        <th>商品分类</th>
        <td><select name="goods_type"><option value="0">--请选择--</option><?php echo $attrOption;?><select></td>
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
        <th>职称：</th>
        <td><input type="text" class="input_w198" name="author_title" value="<?php echo $info['author_title'];?>" /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>规格：</th>
        <td><input type="text" class="input_w198" name="standard" value="<?php echo $info['standard'];?>" /></td><td>&nbsp;</td>
    </tr>
    <tr>
        <th>上架时间：</th>
        <td><input type="text" class="input_w198 timepicker" name="time" value="<?php echo $info['time'];?>" /></td><td>&nbsp;</td>
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
    <label>封面图片：</label>
<?php
     echo "<img src='{$info['thumb_img']}' style='width:200px;height:200px;' />";
?>
</div>
<iframe id="goodsimgiframe" src='/admingoods/addimg/<?php echo $gid;?>'></iframe></div>
    <div>
        <label>相册</label>
        <div id="goods－album">
            <?php
            if($info['album_lists'])
                foreach($info['album_lists'] as $li)
                    echo "<div><img src='{$li['path']}' style='width:200px;height:200px'>&nbsp;&nbsp;<a href='#' onclick='delAlbumImg({$li['id']})'>删除</a></div>";
            ?>
        </div>
        <iframe id="goodsalbumiframe" src='/admingoods/addalbumimg/<?php echo $gid;?>'></iframe></div>
    </div>
</div>
</div>
<script type="text/javascript" src="/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>   
<link href="/css/redmond/jquery-ui-1.8.10.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="/js/jquery-ui-timepicker-zh-CN.js"></script>
<script type='text/javascript'>
$(function(){
     $('.timepicker').datetimepicker({showSecond: true, dateFormat: 'yy-mm-dd', timeFormat: 'hh:mm:ss'});
});
CKEDITOR.replace('goodsbrief', {filebrowserUploadUrl: '/admin/uploadimg'});
function saveGoods()
{
    var postsdata = '';
    $.each($('select'), function(i,n){
        if ($(n).attr('name'))
        	postsdata += '"'+$(n).attr('name')+'":"'+$(n).val()+'",';
    });
    console.log(postsdata);
    postsdata += '"name":"'+$("input[name='goods_name']").val()+'",';
    postsdata += '"author":"'+$("input[name='author_name']").val()+'",';
    postsdata += '"author_title":"'+$("select[name='author_type']").val()+'",';
    postsdata += '"standard":"'+$("input[name='standard']").val()+'",';
    postsdata += '"time":"'+$("input[name='time']").val()+'",';
    postsdata += '"price":"'+$("input[name='price']").val()+'"';

    if ('' == $("input[name='goods_name']").val()){
        alert('商品名不能为空！');return false;
    }
    var gid = parseInt($("input[name='gid']").val());
    $.post('/admingoods/savegoods',{'goods':'{'+postsdata+'}', 'id':gid, 'brief':CKEDITOR.instances.goodsbrief.getData()},function(data){
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
function addblumimg(path, id)
{
    var str = "<div><img src='"+path+"' style=''>&nbsp;&nbsp;<a href='#' onclick='delAlbumImg("+id+");'>删除</a></div>";
    $("#goods－album").append(str);
}
function showimg(path)
{
    var str = "<img src='"+path+"' />";
    jsex.dialog.showmsgauto(str, '图片预览');
}
$(function(){
    $("select[name='goods_type']").live('click',function(){
        var aid = parseInt($(this).val());
        setGoodsAttrOption(aid);
    });

    var gtype = $("select[name='goods_type']").val();
    if (gtype) setGoodsAttrOption(gtype);
    

});
function setGoodsAttrOption(aid)
{
	$('.customattr').remove();
    $.post('/admingoods/getGoodsAttrInfoLists', {'aid': aid}, function(data){
    	<?php
    		if ($info)
    			foreach ($info as $k=>$v){
    				if($k != 'brief')echo "var g_{$k}='{$v}';";
    			}
		?>
    	for(var flag in data['attrs']) {
        	var selectedStr = '<tr class="customattr"><th>'+data['flags'][flag]+'</th><td><select name="'+flag+'">';
        	for(var k in data['attrs'][flag]){
        		var str = '';
            	<?php if($info):?>
            	if(k == eval('g_'+flag)) str = "selected='selected'";
            	<?php endif;?>
            	selectedStr += "<option value='"+k+"'"+str+">"+data['attrs'][flag][k]+"</option>";
            }
            selectedStr += '</selected></td></tr>';
            $('#goodsinfotable tr').eq(0).after(selectedStr);
        }
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
