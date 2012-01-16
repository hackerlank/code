<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?php echo TMConfig::get("base_url"); ?>"/>
<title>single uploading test</title>
<style type="text/css">
.mainbody {margin:30px auto 0;width:800px;}
</style>
</head>
<body>
<div class="mainbody">
<img id="testImg" src="" />
<input id="testUpEntry" type="button" value="上传" />
<?php echo TMDispatcher::loadComponents('singleupload', array('default'))->front(); ?>
<script type="text/javascript">
function onPicUploadSuccess(fileUploadData) {
    if (!fileUploadData.mini)
        fileUploadData.mini = fileUploadData.url;
	jQuery("#testImg").attr("src", fileUploadData.mini);
}

function onPicCutSuccess(cutResultData) {
	jQuery("#testImg").attr("src", cutResultData.mini + "?" + (new Date()).getTime());
}
</script>
</div>
</body>
</html>