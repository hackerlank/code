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
            <dt><a href="#">全部&gt;&gt;</a><span>产品与服务</span></dt>
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
    	<div class="position">当前位置：<a href="/">首页</a>&gt;<a href="/products/lists">产品与服务</a>&gt;<a href="javascript:;"><?php echo $info['proname'];?></a></div>
        <div class="pro-detail">
        	<img src="<?php echo $info['proimg'];?>" class="pro-img" >
        	<h4 class="title"><?php echo $info['proname'];?></h4>
            <p class="detail-list"><label>所属类别：</label> <?php echo $info['typename'];?></p>
            <p class="detail-list"><label>商品简介：</label> <?php echo $info['prodesc'];?></p>
            
            <ul class="pro-tab clearfix">
                <?php
                    if('' != trim($info['proargv'])) 
                        echo '<li><a tab="tab1" href="javascript:;" class="current">产品特点</a></li>';
                    if('' != trim($info['proinfo']))
                        echo '<li><a tab="tab2" href="javascript:;">详细参数</a></li>';
                    if('' != trim($info['proarea']))
                        echo '<li><a tab="tab3" href="javascript:;">应用范围</a></li>';
                    if('' != trim($info['prodown']))
                        echo ' <li><a tab="tab4" href="javascript:;">相关下载</a></li>';
                ?>
            </ul>
            <?php if ('' != trim($info['proargv'])):?>
            <div id="tab1" class="detail-box">
            <?php echo htmlspecialchars_decode($info['proargv']);?>
            </div>
            <?php endif;?>
            <?php if ('' != trim($info['proinfo'])):?>
            <div id="tab2" class="detail-box" style="display:none;">
            <?php echo htmlspecialchars_decode($info['proinfo']);?>
            </div>
            <?php endif;?>
            <?php if ('' != trim($info['proarea'])):?>
            <div id="tab3" class="detail-box" style="display:none;">
            <?php echo htmlspecialchars_decode($info['proarea']);?>
            </div>
            <?php endif;?>
            <?php if ('' != trim($info['prodown'])):?>
            <div id="tab4" class="detail-box" style="display:none;">
            <?php echo htmlspecialchars_decode($info['prodown']);?>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
	$('.pro-tab a').click(function(){
		var id = $(this).attr('tab');
		$('.pro-tab a').removeClass('current');
		$(this).addClass('current');
		$('.detail-box').hide();
		$('#'+id).show();
	});
});
</script>
<?php require_once 'foot.php';?>

