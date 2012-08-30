$(function(){
    var pid = <?php echo $ptype;?>;
    $.post('/goods/attrinfo/'+pid, '', function(data){
        var str = '';
        for(var i=0, iMax=data['list'].length; i < iMax; i++)
            str += "<li><a tid='"+data['list'][i]['id']+"'>"+data['list'][i]['name']+"</a></li>";
        str = "<div class='wp'><h3>"+data['info']['name']+"</h3><ul>"+str+"</ul></div>";
        $("#typelist").html(str);

        $('#typelist ul li a').eq(0).addClass('current');
        var gtype = data['list'][0]['id'];
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
                    tmpStr += "<li><a href='/goods/info/"+listArr[i]['gid']+"'><img src='"+listArr[i][k]['img']+"' width='202px' /></a><p>memo</p><p class='numbox'>喜欢</p></li>";
                }
                str += "<ul class='col-"+(i+1)+"'>"+tmpStr+"</ul>"
            }
            $('.prolist').html(str);
        },'json');
}

