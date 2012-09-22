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
	<h2><?php echo $title;?>列表</h2>
    <p class="page_info">对<?php echo $title;?>进行管理，编辑等</p>
</div>
<div class="s_box">
<label for="newstpyename" style="display:inline;">请选择分类：</label>
<select id="atype"><option value="0">顶级分类</option><?php echo $type_option;?></select>
<input id='gotolists' type="button" class="btn_lv3_1" value="确定" />
</div>
<?php if($list):?>
<table class="datelist-1">
	<thead>
	<tr>
    	<th>新闻类别</th>
    	<th>标题</th>
    	<th>发布日期</th>
    	<th class="r">操作</th>
      </tr>
    </thead>
    <tbody>
    <?php
        $str = '';
        foreach ($list as $row) {
            $str .= '<tr>'.
                    '<td>'.$row['typename'].'</td>'.
                    "<td><a href='/adminarticle/addart/{$row['type']}/{$row['id']}'>{$row['title']}</a></td>".
                    '<td>'.$row['date'].'</td>'.
                    "<td><a href='/adminarticle/addart/{$row['type']}/{$row['id']}' >编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' newsid='{$row['id']}' class='delnews'>删除</a></td>".
                    '</tr>';
        }
        echo $str; 
    ?>
    </tbody>
    </table>
<?php echo $pagination;?>
<?php endif;?>
<script type="text/javascript">
$(function(){
	//设置样式
	$('tr:odd').addClass('eq');
	$("tr").live('mouseover', function(){$(this).addClass('over');});
	$("tr").live('mouseout', function(){$(this).removeClass('over');});
	//删除新闻
	$(".delnews").live('click',function(){
		var id = parseInt($(this).attr('newsid'));
		$.post("/adminarticle/delart","id="+id,function(data){
			jsex.dialog.showmsg(data.msg);
			setTimeout('window.location.reload();',3000);
		},'json');
	});
	$("#gotolists").click(function(){
    var atype = $('#atype').val();
    if (atype) window.location.href= "/adminarticle/lists/1/"+atype;
});
});

</script>
</div>
</body></html>
