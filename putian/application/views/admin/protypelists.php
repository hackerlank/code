<div>
	<h2>产品分类列表</h2>
    <p class="page_info">对产品分类进行管理，编辑等</p>
</div>
<div class="s_box">
<?php
    $str = '';
    function createstype($data, &$str)
    {
        foreach ($data as $v)
            if(!empty($v)) {
                $prestr = str_repeat('&nbsp;&nbsp;',$v['listid']);
                $str .= "<option value='{$v['id']}'>$prestr|--{$v['typename']}</option>";
                if (!empty($v['son']))
                    createstype($v['son'], $str);
            }
    }
    foreach ($list as $row){
        $str .= "<option value='{$row['id']}'>{$row['typename']}</option>";
        if (!empty($row['son']))
            createstype($row['son'],$str);
    } 
?>
<select id="typeid" name="type"><?php echo '<option value="0">顶级分类</option>'.$str;?></select>
<input type="text" name="typename" id="typename" value="" />
<input type="button" value="添加分类" class="btn_lv4_1" onclick="javascript:saveptype();" />
</div>
<table class="datelist-1">
    <tbody>
    <?php
        $str = '';
        function createson($data,&$str)
        {
            foreach ($data as $v) {
                if (!empty($v)) {
                    $prestr = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$v['listid']);
                    $str .= "<tr>".
                            "<td style='text-align: left;'>$prestr|-------<a href='/adminproduct/type/{$v['id']}'>{$v['typename']}</a></td>".
                            "<td><a href='javascript:;' typeid='{$v['id']}' class='deltype'>删除</a></td></td>".
                            "</tr>";
                    if (!empty($v['son'])){
                        createson($v['son'],$str);
                    }
                        
                }
            }
        }
        foreach ($list as $row) {
            $str .= "<tr>".
                    "<td style='text-align: left;'><a href='/adminproduct/type/{$row['id']}'>{$row['typename']}</a></td>".
                    "<td><a href='javascript:;' typeid='{$row['id']}' class='deltype'>删除</a></td>".
                    "</td>";
            if (!empty($row['son']))
                createson($row['son'],$str);
        } 
        echo $str;
    ?>
    </tbody>
    </table>
<script type="text/javascript">
//添加父类
function saveptype()
{
	var typename = $("#typename").val();
	var typeid = $("#typeid").val();
	typename = typename.replace(/\s+/gm,'');
	if ('' == typename) {
		jsex.dialog.showmsg("类型名不能为空");
		return false;
	}
	var postdata = {'pid': typeid,'typename': typename};
	$.post("/adminproduct/savetype",postdata,function(data){
		jsex.dialog.showmsg(data.msg);
		setTimeout('window.location.reload();',3000);
	},'json');
}

$(function(){
	//样式设置
	$("tr:odd").addClass("eq");
	//删除类型
	$(".deltype").live('click',function(){
		var typeid = parseInt($(this).attr("typeid"));
		var postdata = {'id': typeid};
		$.post('/adminproduct/deltype',postdata,function(data){
			jsex.dialog.showmsg(data.msg);
			setTimeout('window.location.reload();',3000);
		},'json');
	});
});
</script>      