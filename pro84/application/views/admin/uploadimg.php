<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>upload</title>
        <script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
    </head>
    <body>
    <form method="post" action="<?php echo $action;?>" enctype="multipart/form-data" id="uploadform">
    <input type="file" name="upload" id="uploadimg" />
    <input type="hidden" name="callback" value="<?php echo $callback;?>" />
    <input type="hidden" name="gid" value="<?php echo $gid;?>" />
    </form>
<script type="text/javascript">
$(function(){
    $('#uploadimg').live('change', function(){
        var gid = parseInt($("input[name='gid']").val());
        if (gid)
            $('#uploadform').submit();
        else
            alert('请填写并保存商品信息后在上次产品图片');
    });
});
</script>
    </body>
</html>
