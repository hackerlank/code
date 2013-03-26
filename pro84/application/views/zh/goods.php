<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>非物质文化遗产</title>
<link href="/css/b.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery.js"></script>
<style type="text/css">
#typelist ul li a {
	cursor: pointer;
}
</style>
</head>

<body>
<div class="head"></div>
<div class="catebox" id="typelist"></div>
<div id='attrlists'></div>
<div class="wp prolist">
<ul class='col-1'></ul>
<ul class='col-2'></ul>
<ul class='col-3'></ul>
<ul class='col-4'></ul>
</div>
<div class='pagination' style='clear:both;'></div>
<div style='display: none;'><input type="hidden" name='goods_total'
	value='0' /></div>
<script type="text/javascript">
var load_completed = false;
var last_load_offset = -1;
var ptype = <?php echo $ptype;?>;
var gtype = <?php echo $gtype;?>;
var page = <?php echo $page;?>;
var maxPrePageNum = 32;

var limitStr = '';

$(function(){
    
    $.post('/goods/typeinfo/'+ptype+'/'+gtype, '', function(data){
        var str = '';
        for(var i=0, iMax=data['list'].length; i < iMax; i++)
            str += "<li><a tid='"+data['list'][i]['id']+"' href='/goods/index/"+ptype+"/"+data['list'][i]['id']+"'>"+data['list'][i]['name']+"</a></li>";
        str = "<div class='wp'><h3>"+data['info']['name']+"</h3><ul>"+str+"</ul>"+
            "<p class='back'><input type='text' name='keywords'><input type='button' value='搜索' onclick='searchGoods();'><a href='javascript:history.go(-1);'>返回</a> | <a href='/'>首页</a></p></div>";

        var attrStr = '';
        $.each(data['attrflag'], function(i,n){
            var tmpStr = '';
            if (undefined != data['attrs'][i]) {
	            $.each(data['attrs'][i], function(j,k){
	                tmpStr += "<li><a attrname='"+i+"' attrid='"+j+"'>"+k+"</a></li>";
	            });
	            attrStr += "<div style='clear:both;' class='wp attrlists'><h5 name='"+i+"'>"+n+":</h5><ul>"+tmpStr+"</ul></div>";
            }
        });
        $("#typelist").html(str);
        $("#attrlists").html(attrStr);

        $('#typelist ul li a[tid="'+gtype+'"]').addClass('current');
        getGoodsLists(gtype, limitStr);

        $('.attrlists li a').live('click', function(){
        	var doc_obj = $(this);
            var curClass = $(this).attr('class');
            if ('current' == curClass) {
                $(this).removeClass('current');
            } else {
	            var attrname = $(this).attr('attrname');
	            $("a[attrname='"+attrname+"']").removeClass('current');
	            $(this).addClass('current');
            }

            limitStr = '';
            $.each($('.attrlists a[class="current"]'), function(i,n){
                limitStr += '"'+$(n).attr('attrname')+'":"'+$(n).attr('attrid')+'",';
            });
            if (limitStr.length>0) limitStr = limitStr.substring(0, limitStr.length-1);
            $('input[name="goods_total"]').val('0');
            last_load_offset = -1;
            load_completed = false;
            $('.col-1').html('');
            $('.col-2').html('');
            $('.col-3').html('');
            $('.col-4').html('');
            getGoodsLists(gtype, limitStr);
        });
        
    },'json');
    

});
function getGoodsLists(gtype, limitStr)
{
   var goods_total = parseInt($('input[name="goods_total"]').val());
   if(goods_total >= maxPrePageNum) return false;
   if (goods_total == last_load_offset) return false;
   last_load_offset = goods_total;
   $.post('/goods/lists/'+gtype+'/'+goods_total, {'limit':"{"+limitStr+"}", 'page':page, 'pagetotal':maxPrePageNum}, function(data){
       if (data['list'].length == 0) load_completed = true;
       loadGoodsHtml(data);
       goods_total += data['list'].length;
       $('input[name="goods_total"]').val(goods_total);

   },'json');
}
function loadGoodsHtml(data)
{
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
            tmpStr += "<li><a href='/goods/info/"+listArr[i][k]['id']+"' target='_blank'><img src='"+listArr[i][k]['thumb_img']+"' width='202px'  /></a><p>"+
                listArr[i][k]['author']+'-'+listArr[i][k]['name']+"</p><p class='numbox'>喜欢（0） ｜ 评论（2）</p></li>";
        }
        var ulClass = 'col-'+(i+1);
        $('.'+ulClass).append(tmpStr);
    }
}
$(window).bind('scroll', function(){
    if ($(document).scrollTop()+$(window).height() > $(document).height()-50) {
        if (!load_completed) getGoodsLists(gtype, limitStr);
    }
});
function searchGoods()
{
    var keywords = $('input[name="keywords"]').val();
    if('' == keywords){
        alert('请输入关键字');
        return false;
    }
    $.post('/goods/search', {'keywords':keywords, 'type':gtype}, function(data){
        $('.col-1').html('');
        $('.col-2').html('');
        $('.col-3').html('');
        $('.col-4').html('');
        loadGoodsHtml(data);
    }, 'json');

}
$.post('/goods/goods_total', {'type':gtype}, function(data){
    var pageNum = Math.ceil(parseInt(data['total']) / maxPrePageNum);
    createPagination(pageNum);
}, 'json');
function createPagination(pageNum)
{
    var maxPageNum = 5;
    var str = '';
    if(pageNum < maxPageNum){
        for(var i = 1 ; i <= pageNum; i++){
            if(i == page) str += "<a href='javascript:void(0);' class='hover'>"+i+"</a>";
            else str += "<a href='/goods/index/"+ptype+"/"+gtype+"/"+i+"'>"+i+"</a>";
        }
    } else {

        if(page <= (pageNum - maxPageNum)){
            for(var i = page ; i < page+maxPageNum; i++){
                if(i == page) str += "<a href='javascript:void(0);' class='hover'>"+i+"</a>";
                else str += "<a href='/goods/index/"+ptype+"/"+gtype+"/"+i+"'>"+i+"</a>";
            }
        } else {
            for(var i = (pageNum - maxPageNum)+1; i <= pageNum; i++){
                if(i == page) str += "<a href='javascript:void(0);' class='hover'>"+i+"</a>";
                else str += "<a href='/goods/index/"+ptype+"/"+gtype+"/"+i+"'>"+i+"</a>";
            }
        }

    }

    str = "<a href='/goods/index/"+ptype+"/"+gtype+"/1'>首页</a>"+str;
    str += "<a href='/goods/index/"+ptype+"/"+gtype+"/"+pageNum+"'>尾页</a>";

    $('.pagination').html(str);
}
</script>
</body>
</html>

