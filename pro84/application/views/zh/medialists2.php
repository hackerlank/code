<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/css/b.css" rel="stylesheet" type="text/css" />
<title>非物质文化遗产</title>
<script language="javascript" src="/js/jquery.js"></script>
</head>

<body>
<div class="head"></div>
<div class="catebox">
    <div class="wp"><h3></h3>
    <ul>
    </ul>
    <p class="back"><a href="javascript:history.go(-1);">返回</a> | <a href="/">首页</a></p></div>
</div>
<div class="wp huodonglist">
    <ul class="clearfix">
    <?php
        foreach ($lists as $li)
            echo "<li><span><img src='{$li['imgurl']}' style='width:204px;height:291px;' /></span> <a href='/media/info/{$li['id']}' target='_blank'>{$li['title']}</a>".
                    "{$li['description']}</li>";
    ?>
    </ul>
    <div class="page" style="display:none;">
        <a href="#">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a>
    </div>
</div>
<script type="text/javascript">
var ptype = <?php echo $ptype;?>;
var stype = <?php echo $stype;?>;
$.post('/media/gettypelists/'+ptype, '', function(data){
    if (data.list) {
        $('.wp h3').text(data['list']['typename']);
        var str = '';
        for (var i=0, iMax=data['list']['son'].length; i<iMax;i++) {
            if (data['list']['son'][i]['id'] == stype) 
                str += "<li><a href='/media/lists/"+ptype+"/"+data['list']['son'][i]['id']+"' class='current'>"+data['list']['son'][i]['typename']+"</a></li>";
            else
                str += "<li><a href='/media/lists/"+ptype+"/"+data['list']['son'][i]['id']+"'>"+data['list']['son'][i]['typename']+"</a></li>";
        }
        $('.catebox ul').html(str);
    }
}, 'json');
</script>
</body>
</html>


