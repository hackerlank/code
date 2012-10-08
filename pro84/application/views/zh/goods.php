<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>非物质文化遗产</title>
<link href="/css/b.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery.js"></script>
<style type="text/css">
#typelist ul li a {cursor:pointer;}
</style>
</head>

<body>
<div class="head"></div>
<div class="catebox" id="typelist">
</div>
<div class="wp prolist">
<ul class='col-1'></ul>
<ul class='col-2'></ul>
<ul class='col-3'></ul>
<ul class='col-4'></ul>
</div>
<div style='display:none;'>
<input type="hidden" name='goods_total' value='0' />
</div>
<script type="text/javascript">
var load_completed = false;
var last_load_offset = -1;
var gtype = 0;
$(function(){
    var pid = <?php echo $ptype;?>;
    $.post('/goods/attrinfo/'+pid, '', function(data){
        var str = '';
        for(var i=0, iMax=data['list'].length; i < iMax; i++)
            str += "<li><a tid='"+data['list'][i]['id']+"'>"+data['list'][i]['name']+"</a></li>";
        str = "<div class='wp'><h3>"+data['info']['name']+"</h3><ul>"+str+"</ul><p class='back'><a href='javascript:history.go(-1);'>返回</a> | <a href='/'>首页</a></p></div>";
        $("#typelist").html(str);

        <?php 
            if ($gtype) echo "gtype=$gtype;";
            else echo "gtype=data['list'][0]['id'];";
        ?>
        $('#typelist ul li a[tid="'+gtype+'"]').addClass('current');
        getGoodsLists(gtype);

        $("#typelist ul li a").live('click', function(){
            $('#typelist ul li a').removeClass('current');
            $(this).addClass('current');
            gtype = parseInt($(this).attr('tid'));
            $('.col-1').html('');
            $('.col-2').html('');
            $('.col-3').html('');
            $('.col-4').html('');
            $('input[name="goods_total"]').val(0);
            last_load_offset = -1;
            load_completed = false;
            getGoodsLists(gtype);
        });
    },'json');
    

});
function getGoodsLists(gtype)
{
   var goods_total = parseInt($('input[name="goods_total"]').val());
   if (goods_total == last_load_offset) return false;
   last_load_offset = goods_total;
   $.post('/goods/lists/'+gtype+'/'+goods_total, '', function(data){
       if (data['list'].length == 0) load_completed = true;
       var listArr = new Array();
       listArr[0] = new Array();
       listArr[1] = new Array();
       listArr[2] = new Array();
       listArr[3] = new Array();
       for(var i=0,j=0, iMax=data['list'].length;i < iMax;i++,j++) {
           if (3 < j) j = 0;
           listArr[j].push(data['list'][i]);
       }
       var str = '';
       for (var i=0, iMax=listArr.length; i < iMax; i++) {
           var tmpStr = '';
           for (k in listArr[i]) {
               tmpStr += "<li><a href='/goods/info/"+listArr[i][k]['id']+"'><img src='"+listArr[i][k]['thumb_img']+"' width='202px' /></a><p>"+
                         listArr[i][k]['author']+'-'+listArr[i][k]['name']+"</p><p class='numbox'>喜欢（0） ｜ 评论（2）</p></li>";
           }
           var ulClass = 'col-'+(i+1);
           $('.'+ulClass).append(tmpStr);
       }

       goods_total += data['list'].length;
       $('input[name="goods_total"]').val(goods_total); 
   },'json');
}
$(window).bind('scroll', function(){
    if ($(document).scrollTop()+$(window).height() > $(document).height()-50) {
        if (!load_completed) getGoodsLists(gtype);
    }
});
</script>
</body>
</html>

