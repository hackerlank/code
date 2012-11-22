<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/css/b.css" rel="stylesheet" type="text/css" />
<title>非物质文化遗产</title>
<script type="text/javascript" src="/js/jquery.js"></script>
</head>

<body>
<div class="head"></div>
<div class="catebox" id="typelist">
</div>
<div class="wp">
	<div class="detail clearfix">
    <h2 class="pro-name"><?php echo $info['name'];?></h2>
    	<div class="detail-img"><img src="<?php echo $info['img'];?>" class="detailimg" style="max-width:738px;display:none;" /></div>
        <div class="pro-detail-r">
        	<p><strong>作者：</strong><?php echo $info['author'];?></p>
        	<?php
        		foreach ($attrflag as $k=>$v){
        			$attrval = $info[$k];
        			if(!empty($attrval))
        				echo "<p><strong>{$v}:</strong>{$attrs[$k][$attrval]}</p>";
        		} 
        	?>
            <p><strong>简介:</strong></p>
            <div class="txt"><?php echo $info['brief'];?></div>
        </div>
    </div>
</div>
<div id="qqfloat" style="width:90px; height:150px;z-index: 999; left: 90%; visibility: visible; position: absolute; top: 259px;">
<div style="width:8px; height:150px; float:left; background:url(http://im.bizapp.qq.com:8000/bg_1.gif) no-repeat;"></div>
<div style="float:left; width:74px; height:150px; background:url(http://im.bizapp.qq.com:8000/middle.jpg) no-repeat; ">
        <div style="height:80px;  clear:both;margin-top:40px;width:74px; float:left; background:url(/images/face.jpg) no-repeat;"></div>
        <div style="clear:both;width:74px;">
        <a style="padding-top:15px;height:20px; background:url(http://im.bizapp.qq.com:8000/btn_2.gif) no-repeat;  display:block;" target="_blank" href="tencent://message/?uin=71811124"></a>
        </div>
</div>
<div style="width:8px; height:150px; float:left; background:url(http://im.bizapp.qq.com:8000/bg_1.gif) right;"></div>
</div>
<script type="text/javascript">
$(function(){
	var ptype = <?php echo $ptype;?>;
	var gtype = <?php echo $gtype;?>;
	
	 $.post('/goods/typeinfo/'+ptype+'/'+gtype, '', function(data){
	        var str = '';
	        for(var i=0, iMax=data['list'].length; i < iMax; i++)
	            str += "<li><a tid='"+data['list'][i]['id']+"' href='/goods/index/"+ptype+"/"+data['list'][i]['id']+"'>"+data['list'][i]['name']+"</a></li>";
	        str = "<div class='wp'><h3>"+data['info']['name']+"</h3><ul>"+str+"</ul><p class='back'><a href='javascript:history.go(-1);'>返回</a> | <a href='/'>首页</a></p></div>";

	        $("#typelist").html(str);

	        $('#typelist ul li a[tid="'+gtype+'"]').addClass('current');
	        
	    },'json');

});

var imgsrc = $('.detailimg').attr("src");
var img_tmp = new Image();
img_tmp.src = imgsrc;
if (img_tmp.complete)
{
	showimg(img_tmp);
} else {
	img_tmp.onload = function () {
		showimg(img_tmp);
	}
}

function showimg(img)
{
	if (img.width > 738)
	{
		var width = 738;
		var height = parseInt((img.height*738)/img.width);
	}else {
		var width = img.width;
		var height = img.height;
	}
	$('.detailimg').attr('width', width);
	$('.detailimg').attr('height', height);
	$('.detailimg').show();
}

var end_point=325;
function qqmenu(){
    var starp,endp;
    starp=parseInt($('#qqfloat').css("top"),10);
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    //endp=end_point+document.documentElement.scrollTop;
    endp=end_point+scrollTop;
    if(starp!=endp){
        var scrollp=Math.ceil( Math.abs( endp - starp ) / 15 );
        var n=parseInt($('#qqfloat').css("top"),10)+((endp<starp)?-scrollp:scrollp);
        $('#qqfloat').css("top",n);
    }
    setTimeout("qqmenu()",20);
    //$('#error').text(scrollTop);
}
qqmenu();
</script>
</body>
</html>

</body>
</html>
