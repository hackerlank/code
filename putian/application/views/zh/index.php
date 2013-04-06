<?php require_once 'head.php';?>
<div class="wp clearfix">
	<div class="index-l">
<div class="hot-img"><script src="/js/swfobject_source.js" type=text/javascript></script>

<div id=dplayer2 style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; BACKGROUND: #ffffff; PADDING-BOTTOM: 0px; MARGIN: 0px auto; WIDTH: 304px; PADDING-TOP: 0px; HEIGHT: 222px"></div>

<SCRIPT language=javascript type=text/javascript>
var titles = '<?php echo $newsimglist[0]['title'].'|'.$newsimglist[1]['title'].'|'.$newsimglist[2]['title'].'|'.$newsimglist[3]['title'];?>';
var imgs='<?php echo $newsimglist[0]['imgurl'].'|'.$newsimglist[1]['imgurl'].'|'.$newsimglist[2]['imgurl'].'|'.$newsimglist[3]['imgurl'];?>';
var urls='<?php echo 'media/info/'.$newsimglist[0]['id'].'|media/info/'.$newsimglist[1]['id'].'|media/info/'.$newsimglist[2]['id'].'|media/info/'.$newsimglist[3]['id'];?>';
var pw = 304;
var ph = 222;
var sizes = 14;
var Times = 4000;
var umcolor = 0xFFFFFF;
var btnbg =0xFF7E00;
var txtcolor =0xFFFFFF;
var txtoutcolor = 0x000000;
var flash = new SWFObject('flash/focus1.swf', 'mymovie', pw, ph, '7', '');
flash.addParam('allowFullScreen', 'true');
flash.addParam('allowScriptAccess', 'always');
flash.addParam('quality', 'high');
flash.addParam('wmode', 'Transparent');
flash.addVariable('pw', pw);
flash.addVariable('ph', ph);
flash.addVariable('sizes', sizes);
flash.addVariable('umcolor', umcolor);
flash.addVariable('btnbg', btnbg);
flash.addVariable('txtcolor', txtcolor);
flash.addVariable('txtoutcolor', txtoutcolor);
flash.addVariable('urls', urls);
flash.addVariable('Times', Times);
flash.addVariable('titles', titles);
flash.addVariable('imgs', imgs);
flash.write('dplayer2');
</SCRIPT></div>
    	
        
        <div class="mainbox index-news">
        	<h3><a href="/media/lists" class="more">更多 &gt;&gt;</a><span>公司新闻</span></h3>
            <div class="mainbox-bd">
            	<ul>
            	<?php foreach ($newslist as $v):?>
            	<li><span><?php echo $v['date']?></span><a href='/media/info/<?php echo $v['id'];?>' title='<?php echo $v['title'];?>'><?php echo $v['subtitle'];?></a></li>
            	<?php endforeach;?>
                </ul>
            </div>
        </div>
        
        <div class="mainbox index-pro">
        	<h3><a href="/products/lists" class="more">更多 &gt;&gt;</a><span>公司产品</span></h3>
            <div class="mainbox-bd">
            	<ul>
            	<?php
            	    $str ='';
            	    foreach ($productlist as $v) {
            	        $str .= "<li><p><a href='products/info/{$v['id']}'><img src='{$v['proimg']}' /></a></p><a href='products/info/{$v['id']}' title='{$v['proname']}' class='name'>{$v['prosubname']}</a></li>";
            	    } 
            	    echo $str;
            	?>
                </ul>
            </div>
        </div>
        
        <div class="mainbox index-news" style=" width:347px; margin-right:9px;">
        	<h3><a href="/media/cases" class="more">更多 &gt;&gt;</a><span>解决方案</span></h3>
            <div class="mainbox-bd">
            	<ul>
            	<?php 
            	$str ='';
            	foreach ($prolist as $v) {
            	    $str .= "<li><span>[{$v['date']}]</span><a href='/media/info/{$v['id']}' title='{$v['title']}'>{$v['subtitle']}</a></li>";
            	}
            	echo $str;
            	?>
                </ul>
            </div>
        </div>
        <div class="mainbox index-news" style=" width:347px;">
        	<h3><a href="/media/example" class="more">更多 &gt;&gt;</a><span>成功案例</span></h3>
            <div class="mainbox-bd">
                <ul>
                    <?php
                    $str ='';
                    foreach ($example as $v) {
                        $str .= "<li><span>[{$v['date']}]</span><a href='/media/info/{$v['id']}' title='{$v['title']}'>{$v['subtitle']}</a></li>";
                    }
                    echo $str;
                    ?>
                </ul>
            </div>
        </div>
        
    </div>
    <div class="index-r">
    	<div class="mainbox">
        	<h3><span class="f14">关于普天</span></h3>
            <div class="mainbox-bd">
            	<p class="index-about"><?php echo $aboutus;?>...<a href="/page/aboutus">更多&gt;&gt;</a></p>
            </div>
        </div>
        <div class="adv-img-1"><img src="images/img-1.jpg" /></div>
        <div class="mainbox">
            <h3><span class="f14">公司荣誉</span></h3>
            <div class="mainbox-bd">
                <ul>
                    <?php
                    $str ='';
                    foreach ($pimg as $v) {
                        $str .= "<li><span>[{$v['date']}]</span><a href='/media/info/{$v['id']}' title='{$v['title']}'>{$v['subtitle']}</a></li>";
                    }
                    echo $str;
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once 'foot.php';?>
