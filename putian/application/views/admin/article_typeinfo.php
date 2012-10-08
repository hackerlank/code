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
    <th></th>
    <td><input type="button" value="保 存" class="btn_lv3_1" onclick="savetype();" /></td>
    </tr>
</table>
<script type="text/javascript">
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
	var postdata = {'id': typeid, 'pid': pid, 'typename': typename}
	$.post('/adminarticle/updatetype',postdata,function(data){
		jsex.dialog.showmsg(data.msg);
		setTimeout('window.location.reload();',3000);
	},'json');
}
</script>