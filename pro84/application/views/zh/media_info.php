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
    <p class="back"><a href="#">返回</a> | <a href="/">首页</a></p></div>
    </div>
</div>
<div class="wp">
    <div class="newsdetail">
        <h2 class="title"><?php echo $info['title'];?></h2>
        <div class="news-info"><?php echo $info['content'];?></div>
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

