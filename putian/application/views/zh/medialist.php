<?php
/**
 *@author:shenjian@ztgame.com
 *@encoding=UTF-8 ts=4 sw=4
 *@create:2012-4-4
 */
?>
<?php require_once 'head.php';?>
<div class="wp clearfix">
    <div class="pro-l">
        <dl class="pro-cate">
            <dt><a href="<?php echo $baseurl;?>">全部&gt;&gt;</a><span>新闻资讯</span></dt>
            <?php
            $str = '';
            foreach ($typelist as $v) {
                $str .= "<dd><a href='{$baseurl}/{$v['id']}'>{$v['typename']}</a></dd>";
            } 
            echo $str;
            ?>
            
        </dl>
        <img src="/images/img-1.jpg" style=" width:220px; margin-top:10px;" />
    </div>
    <div class="pro-r">
    	<h4 class="page-cate"><span><?php echo $typename;?></span></h4>
    	<ul class="news">
    	<?php
    	$str = '';
    	foreach ($lists['list'] as $v) {
    	    $str .= "<li><span>{$v['date']}</span><a href='/media/info/{$v['id']}'>{$v['title']}</a></li>";
    	} 
    	echo $str;
    	?>
		</ul>
<?php if (isset($page)) echo $page;?>
    </div>
</div>

<?php require_once 'foot.php';?>
