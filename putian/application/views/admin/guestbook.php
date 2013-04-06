
<table class="datelist-1">
	<thead>
	<tr>
    	<th>姓名</th>
    	<th>电话</th>
        <th>内容</th>
    	<th>邮箱</th>
        <th>添加时间</th>
      </tr>
    </thead>
    <tbody>
    <?php
        $str = '';
        foreach ($lists as $row) {
            $row['addtime'] = date("Y-m-d H:i:s", $row['addtime']);
            $str .= "<tr>".
                "<td>{$row['name']}</td>".
                "<td>{$row['tel']}</td>".
                "<td>{$row['content']}</td>".
                "<td>{$row['email']}</td>".
                "<td>{$row['addtime']}</td></tr>";
        }
        echo $str; 
    ?>
    </tbody>
    </table>
<script type="text/javascript">
$(function(){
	//设置样式
	$('tr:odd').addClass('eq');
	<?php 
	if (1== $type) 
	    echo  '$(".fed-menu-list li").removeClass("current").eq(1).addClass("current");';
	elseif (2 == $type)
	    echo '$(".fed-menu-list li").removeClass("current").eq(5).addClass("current");';
	?>
});
</script>
