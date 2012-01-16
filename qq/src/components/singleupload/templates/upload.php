<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $i18n['title']; ?></title>
<link type="text/css" rel="stylesheet" href="<?php echo TMConfig::get("base_url"); ?>components/singleupload/css/app_<?php echo $app; ?>.css" />
<?php if ($enableFromQZone) { ?>
<script type="text/javascript" charset="gb2312" src="http://imgcache.qq.com/qzone/biz/comm/js/brand_release.js"></script>
<?php } ?>
</head>
<body>
<script type="text/javascript">
document.domain = "qq.com";

function onUploading() {
    document.getElementById("uploadform").style.display = "none";
    document.getElementById("uploading").style.display = "";
}
</script>
<?php if ($enableFromQZone) { ?>
<div id="uploadSelect" style="margin:40px 0px 0px 100px;">
    <input type="button" name="fromLocal" value="本地上传" onclick="document.getElementById('uploadSelect').style.display='none';document.getElementById('uploadform').style.display='';" />
    <input type="button" name="fromQzone" value="从QQ相册" onclick="parent.uploadPicFromQQAlbum('<?php echo $app; ?>');" />
</div>
<div id="uploadform" style="display:none;">
<?php } else { ?>
<div id="uploadform">
<?php } ?>
<form enctype="multipart/form-data" action="<?php echo TMConfig::get("base_url"); ?>singleupload/upload/doupload?app=<?php echo $app; ?>&callbackFunName=<?php echo $callbackFunName; ?>&cutCallbackFunName=<?php echo $cutCallbackFunName; ?>" method="post" onsubmit="return onUploading();">
  <div class="uploadBtn">
    <dl>
      <dt>图片：</dt>
      <dd><input type="file" id="fileSrc" name="fileSrc" size="36" value="<?php echo $i18n['browse']; ?>" class="uploadInput" /></dd>
    </dl>
    <dl>
      <dt>说明：</dt>
      <dd><input type="text" id="fileDesc" name="fileDesc" size="50" value="" /></dd>
    </dl>
    <dl>
      <input type="submit" value="<?php echo $i18n['upload']; ?>" />
    </dl>
  </div>
  <div class="uploadTxt"><?php echo $i18n['intro']; ?></div>
</form>
</div>
<div id="uploading" style="display:none;">
  <div class="uploadLoading"><img src="<?php echo TMConfig::get("base_url"); ?>components/singleupload/images/loadingAnimation.gif" ></div>
</div>
</body>
</html>