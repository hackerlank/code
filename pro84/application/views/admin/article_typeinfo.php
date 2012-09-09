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
	<h2><?php echo $title;?>类别</h2>
    <p class="page_info">对<?php echo $title;?>类别进行管理，编辑等</p>
</div>
<table class="bk_form_tbl">
    <tr>
        <th>上级分类：</th>
        <td>
        <?php
            $str = '';
            function createstype($data, &$str,$pid)
            {
                foreach ($data as $k=>$v) {
                    if(!empty($v)) {
                        $prestr = str_repeat('&nbsp;&nbsp;',$v['listid']);
                        if ($pid==$v['id']) 
                            $selected = " selected='selected'";
                        else 
                            $selected = '';
                        $str .= "<option $selected value='{$v['id']}'>$prestr|--{$v['typename']}</option>";
                        if (!empty($v['son']))
                            createstype($v['son'], $str,$pid);
                    }
                }
                    
            }
            foreach ($typelist as $row){
                if ($info['pid']==$row['id']) 
                    $selected = " selected='selected'";
                else 
                    $selected = '';
                $str .= "<option $selected value='{$row['id']}'>{$row['typename']}</option>";
                if (!empty($row['son']))
                    createstype($row['son'],$str,$info['pid']);
            } 
        ?>
        <select id="typepid"><option value="0" <?php if(0==$info['pid']) echo ' selected="selected"';?>>顶级分类</option><?php echo $str;?></select></td>
    </tr>
    <tr>
    	<th>分类名：</th>
    	<td>
    		<input type="text" id="typename" value="<?php echo $info['typename'];?>" />
    		<input type="hidden" id="typeid" value="<?php echo $info['id'];?>" />
    	</td>
    </tr>
    <tr>
        <th>列表模版</th>
        <td>
        <input type="radio" name="template" value="medialists1" />模版一<span onclick="javascript:showimg('/images/media/medialists1.jpg');">[预览]</span>
        <input type="radio" name="template" value="medialists2" />模版一<span onclick="javascript:showimg('/images/media/medialists2.jpg');">[预览]</span>
        <input type="radio" name="template" value="medialists3" />模版一<span onclick="javascript:showimg('/images/media/medialists3.jpg');">[预览]</span>
        </td>
    </tr>
    <tr>
    <th></th>
    <td><input type="button" value="保 存" class="btn_lv3_1" onclick="savetype();" /></td>
    </tr>
</table>
<script type="text/javascript">
<?php
if ($info['template'])
 echo '$("input[value=\''.$info['template'].'\']").attr("checked","checked");';
?>
function savetype()
{
	var pid = parseInt($("#typepid").val());
	var typename = $("#typename").val();
	var typeid = parseInt($("#typeid").val());
	typename = typename.replace(/\s+/gm,'');
	if('' == typename) {
		jsex.dialog.showmsg("类名不能为空");
		return false;
	}
	if (pid==typeid) pid=0;
    var template = $('input[name="template"]:checked').val();
    if (undefined == template){jsex.dialog.showmsg('请选择模版');return false;}
	var postdata = {'id': typeid, 'pid': pid, 'typename': typename,'template':template};
	$.post('/adminarticle/updatetype',postdata,function(data){
		jsex.dialog.showmsg(data.msg);
		setTimeout('window.location.reload();',3000);
	},'json');
}
function showimg(path)
{
    var str = "<img src='"+path+"' />";
    jsex.dialog.showmsgauto(str, '图片预览');
}
</script>
</body></html>
