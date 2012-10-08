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
            <dt><a href="#">全部&gt;&gt;</a><span>新闻资讯</span></dt>
            <?php
            $str = '';
            foreach ($typelist as $v) {
                $str .= "<dd><a href='/media/lists/{$v['id']}'>{$v['typename']}</a></dd>";
            } 
            echo $str;
            ?>
        </dl>
        <img src="/images/img-1.jpg" style=" width:220px; margin-top:10px;" />
    </div>
    <div class="pro-r">
    	<h4 class="page-cate"><span><?php echo $info['typename'];?></span></h4>
    	<h3 class="news-title"><?php echo $info['title'];?></h3>
		<div class="news-detail"><?php echo $info['content'];?></div>
    </div>
</div>
<?php require_once 'foot.php';?>
