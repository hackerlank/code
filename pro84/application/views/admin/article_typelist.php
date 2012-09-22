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
	<h2><?php echo $title;?>类型列表</h2>
    <p class="page_info">对<?php echo $title;?>类型进行管理，编辑等</p>
</div>
<div class="s_box">
<label for="newstpyename" style="display:inline;">类别：</label>
<select id="typepid"><option value="0">顶级分类</option><?php echo $type_option;?></select>
<input type="hidden" name="atype" id="atype" value="<?php echo $type;?>" /> 
<input type="text" name="typename" id="typename" value="" />    
<button class="btn_lv4_1" onclick="javascript:addnewstype();">添加</button>
</div>
        <table class="datelist-1">
    <?php
        $str = '';
        function createson($data,&$str,$type)
        {
            foreach ($data as $v) {
                if (!empty($v)) {
                    $prestr = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$v['listid']);
                    $str .= "<tr>".
                            "<td style='text-align: left;'>$prestr|-------<a href='/adminarticle/typeinfo/{$type}/{$v['id']}'>{$v['typename']}</a></td>".
                            "<td><a href='javascript:;' typeid='{$v['id']}' class='deltype'>删除</a></td></td>".
                            "</tr>";
                    if (!empty($v['son'])){
                        createson($v['son'],$str,$type);
                    }
                        
                }
            }
        }
        foreach ($typelist as $row) {
            $str .= "<tr>".
                    "<td style='text-align: left;'><a href='/adminarticle/typeinfo/{$type}/{$row['id']}'>{$row['typename']}</a></td>".
                    "<td><a href='javascript:;' typeid='{$row['id']}' class='deltype'>删除</a></td>".
                    "</td>";
            if (!empty($row['son']))
                createson($row['son'],$str,$type);
        } 
        echo $str;
    ?>
            <tbody>
            </tbody>
            </table>
<script type="text/javascript">
$(function(){
	//设置样式
	$('tr:odd').addClass('eq');
	<?php 
	if (1== $type) 
	    echo  '$(".fed-menu-list li").removeClass("current").eq(0).addClass("current");';
	elseif (2 == $type)
	    echo '$(".fed-menu-list li").removeClass("current").eq(4).addClass("current");';
	?>
	//删除
	$(".deltype").live('click',function(){
		var id = parseInt($(this).attr('typeid'));
		$.post("/adminarticle/deltype","id="+id,function(data){
			jsex.dialog.showmsg(data.msg);
			setTimeout('window.location.reload();',3000);
		},'json');
	});
});
function addnewstype()
{
	var typename = $("#typename").val();
	typename = typename.replace(/\s+/gm,'');
	if ('' == typename) {
		jsex.dialog.showmsg("类型名不能为空");
		setTimeout('window.location.reload();',3000);
	}
	var pid = $('#typepid').val();
	var type = $('#atype').val();

	var postdata = {'pid': pid, 'typename': typename,'type':type}
	$.post('/adminarticle/addtype',postdata,function(data){
		jsex.dialog.showmsg(data.msg);
		if (0 == data.code)
			setTimeout('window.location.reload();',3000);
	},'json');
}
</script>
</div>
</body>
</html>
