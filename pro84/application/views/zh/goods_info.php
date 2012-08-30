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
    	<img src="<?php echo $info['img'];?>" class="detail-img" width="738px" height="738px">
        <div class="pro-detail-r">
        	<p><strong>作者：</strong><?php echo $info['author'];?></p>
            <p><strong>作者分类：</strong><?php echo $info['author_type_nmae'];?></p>
            <p><strong>作品类型：</strong></p>
            <p><strong>工艺：</strong><?php echo $info['craft_name'];?></p>
            <p><strong>题材：</strong><?php echo $info['theme_name'];?></p>
            <p><strong>创作时间：</strong><?php echo $info['age_name']?></p>
            <p><strong>简介:</strong></p>
            <div class="txt"><?php echo $info['brief'];?></div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    var pid = <?php echo $ptype;?>;
    $.post('/goods/attrinfo/'+pid, '', function(data){
        var str = '';
        for(var i=0, iMax=data['list'].length; i < iMax; i++)
            str += "<li><a tid='"+data['list'][i]['id']+"' href='/goods/index/<?php echo $ptype;?>/"+data['list'][i]['id']+"'>"+data['list'][i]['name']+"</a></li>";
        str = "<div class='wp'><h3>"+data['info']['name']+"</h3><ul>"+str+"</ul></div>";
        $("#typelist").html(str);

        $('#typelist ul li a').eq(0).addClass('current');

        $("#typelist ul li a").live('click', function(){
            $('#typelist ul li a').removeClass('current');
            $(this).addClass('current');
            var gtype = parseInt($(this).attr('tid'));
            getGoodsLists(gtype);
        });
    },'json');
    

});
function getGoodsLists(gtype)
{
        $.post('/goods/lists/'+gtype, '', function(data){
            var listArr = [];
            var tempArr = [];
            for(var i=0,j=1, iMax=data['list'].length;i < iMax;i++,j++) {
                tempArr.push(data['list'][i]);
                if (3 ==j) {
                    listArr.push(tempArr);
                    tempArr = [];
                    j = 1;
                }
            }
            if (tempArr.length>0) listArr.push(tempArr);
            var str = '';
            for (var i=0, iMax=listArr.length; i < iMax; i++) {
                var tmpStr = '';
                for (k in listArr[i]) {
                    tmpStr += "<li><a href='/goods/info/"+listArr[i][k]['id']+"'><img src='"+listArr[i][k]['img']+"' width='202px' /></a><p>"+listArr[i][k]['name']+"</p><p class='numbox'>喜欢</p></li>";
                }
                str += "<ul class='col-"+(i+1)+"'>"+tmpStr+"</ul>"
            }
            $('.prolist').html(str);
        },'json');
}
</script>
</body>
</html>

</body>
</html>
