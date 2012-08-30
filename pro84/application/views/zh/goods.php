<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>非物质文化遗产</title>
<link href="/css/b.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery.js"></script>
</head>

<body>
<div class="head"></div>
<div class="catebox" id="typelist">
</div>
<div class="wp prolist">
</div>
<script type="text/javascript">
$(function(){
    var pid = <?php echo $ptype;?>;
    $.post('/goods/attrinfo/'+pid, '', function(data){
        var str = '';
        for(var i=0, iMax=data['list'].length; i < iMax; i++)
            str += "<li><a tid='"+data['list'][i]['id']+"'>"+data['list'][i]['name']+"</a></li>";
        str = "<div class='wp'><h3>"+data['info']['name']+"</h3><ul>"+str+"</ul></div>";
        $("#typelist").html(str);

        <?php 
            if ($gtype) echo "var gtype=$gtype;";
            else echo "var gtype=data['list'][0]['id'];";
        ?>
        $('#typelist ul li a[tid="'+gtype+'"]').addClass('current');
        getGoodsLists(gtype);

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
               tmpStr += "<li><a href='/goods/info/"+listArr[i][k]['id']+"'><img src='"+listArr[i][k]['img']+"' width='202px' /></a><p>"+
                         listArr[i][k]['author']+'-'+listArr[i][k]['name']+"</p><p class='numbox'>喜欢（0） ｜ 评论（2）</p></li>";
           }
           str += "<ul class='col-"+(i+1)+"'>"+tmpStr+"</ul>"
       }
       $('.prolist').html(str);
   },'json');
}
</script>
</body>
</html>

