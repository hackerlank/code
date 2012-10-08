<div>
	<h2>产品列表</h2>
    <p class="page_info">对产品进行管理，编辑等</p>
</div>
<div class="s_box">
<a class="btn_lv4_1" href="/adminproduct/addproduct">添加产品</a>
</div>
        <table class="datelist-1">
        	<thead>
        	<tr>
            	<th>产品类别</th>
            	<th>产品名</th>
                <th>首页显示</th>
                <th>排序</th>
            	<th class="r">操作</th>
              </tr>
            </thead>
            <?php
                $str = '';
                foreach ($list as $row) {
                    $str .= '<tr>'.
                            "<td>{$row['typename']}</td>".
                            "<td><a href='/adminproduct/addproduct/{$row['id']}'>{$row['proname']}</a></td>";
                    if ($row['showindex'])
                        $str .= "<td><input name='isshow{$row['id']}' class='isshowindex' pid='{$row['id']}' type='radio' checked='checked' value='1' />是<input name='isshow{$row['id']}' pid='{$row['id']}' class='isshowindex' type='radio' value='0' />否</td>";
                    else 
                        $str .= "<td><input name='isshow{$row['id']}' pid='{$row['id']}' class='isshowindex' type='radio' value='1' />是<input name='isshow{$row['id']}' pid='{$row['id']}' type='radio' class='isshowindex' value='0' checked='checked' />否</td>";        
                    $str .= "<td><input type='text' pid='{$row['id']}' class='pordernum' value='{$row['ordernum']}' /></td>".
                            "<td><a href='/adminproduct/addproduct/{$row['id']}' >编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:;' class='del' proid='{$row['id']}' >删除</a></td>".
                            '</tr>';
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
	$(".fed-menu-list li").removeClass("current").eq(3).addClass("current");
	//删除
	$(".del").live('click',function(){
		var proid = $(this).attr('proid');
		if(proid) {
			$.post('/adminproduct/delpro/'+proid,'',function(data){
				alert(data.msg);
				if (0 == data.err) window.location.reload();
			},'json');
		}
	});
    //是否展示首页
    $('.isshowindex').live('click',function() {
        var isshow = parseInt($(this).val());
        var pid = parseInt($(this).attr('pid'));
        var postdata = {'isshow': isshow, 'id': pid}
        $.post('/adminproduct/isshow',postdata,function(data){
            jsex.dialog.showmsg(data.msg);    
        },'json');
    });
    //更改顺序
    $(".pordernum").live('change',function(){
        var ordernum = parseInt($(this).val());
        var id = parseInt($(this).attr('pid'));
        if(!ordernum) {
            jsex.dialog.showmsg('请输入正确的数字');
            return false;
        }
        var postdata = {'id': id, 'ordernum': ordernum}
        $.post('/adminproduct/ordernum',postdata,function(data){
            jsex.dialog.showmsg(data.msg);
        },'json');
    });
});
</script>
