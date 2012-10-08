<?php require_once 'head.php';?>
<div class="wp clearfix">
    <div class="pro-l">
        <dl class="pro-cate">
            <dt><a href="/products/lists">全部&gt;&gt;</a><span>产品与服务</span></dt>
           <?php 
            $str ='';
            foreach ($typelist as $v) {
                if (!empty($v['son'])) {
                    $strs = '';
                    foreach ($v['son'] as $v1){
                        $strs .= "<li><a href='/products/lists/{$v1['id']}'>{$v1['typename']}</a></li>";
                    }
                    $str .= "<dd><a href='/products/lists/{$v['id']}'>{$v['typename']}</a><ul>".$strs.'</ul></dd>';
                } else 
                    $str .= "<dd><a href='/products/lists/{$v['id']}'>{$v['typename']}</a></dd>";
            }
            echo $str;
            ?>
        </dl>
        <img src="/images/img-1.jpg" style=" width:220px; margin-top:10px;" />
    </div>
    <div class="pro-r">
    	<h4 class="page-cate"><span><?php echo $category_title;?></span></h4>
    	<ul class="product-list clearfix">
    	<?php
    	$str = '';
    	foreach ($plist as $v) {
    	    $str .= "<li><img src='{$v['proimg']}' /><h4>{$v['proname']}</h4><p></p> <a href='/products/info/{$v['id']}' class='more'>查看详细&gt;&gt;</a></li>";
    	} 
    	echo $str;
    	?>
        </ul>
        <?php if (isset($page)) echo $page;?>
    </div>
</div>

<?php require_once 'foot.php';?>
