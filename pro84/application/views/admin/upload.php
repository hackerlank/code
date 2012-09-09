<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>upload</title>
	<script language="javascript" src="/js/jquery.js"></script>
</head>
<body>
<form action="/admin/uploadimg/<?php echo $callback?>" id="uploadform"  enctype="MULTIPART/FORM-DATA" method="post">
<input type="file" name="upload" id="imgupload" />
</form>
<script type="text/javascript">
$(function(){
	$("#imgupload").live('change',function(){
		$("#uploadform").submit();
	});
});

</script>
</body>
