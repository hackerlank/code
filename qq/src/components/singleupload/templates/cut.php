<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $i18n['title']; ?></title>
<link type="text/css" rel="stylesheet" href="<?php echo TMConfig::get("base_url"); ?>components/singleupload/css/app_<?php echo $app; ?>.css" />
<script type="text/javascript">
document.domain = "qq.com";
</script>
<script src="<?php echo TMConfig::get("base_url"); ?>components/singleupload/js/jquery-1.3.2.js" type="text/javascript"></script>
<script src="<?php echo TMConfig::get("base_url"); ?>components/singleupload/js/jquery.imgareaselect-0.4.2.min.js" type="text/javascript"></script>
</head>
<body>
<?php if (!empty($message)) { ?>
<div class="cutMessage">
<?php echo $message; ?>
</div>
<?php } else { ?>
<div class="cutIntro"><?php echo $i18n['intro']; ?></div>
<div class="cutContainer">
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" class="cutSelectorTd"><img id="areaSelectImg" src="<?php echo $orig_url; ?>" style="width:<?php echo $fixedsize['w']; ?>px;height:<?php echo $fixedsize['h']; ?>px;" /></td>
    <td align="center" valign="top" class="cutPreviewTd"><div class="cutPreviewOuter" style="width:<?php echo $thumb['width']; ?>px;height:<?php echo $thumb['height']; ?>px;">
        <div id="previewDiv" class="cutPreviewInner" style="width:<?php echo $thumb['width']; ?>px;height:<?php echo $thumb['height']; ?>px;"><img id="previewImg" class="cutPreviewImg" style="width:<?php echo $fixedsize['w']; ?>px;height:<?php echo $fixedsize['h']; ?>px;" src="<?php echo $orig_url; ?>" /></div>
      </div>
      <br />
      <br />
      <input type="button" name="confirmcutbtn" value="<?php echo $i18n['btn']; ?>" border="0" onClick="confirmCut();" />
    </td>
  </tr>
</table>
</div>
<div id="messageDiv" class="cutSaving"><?php echo $i18n['saving']; ?></div>
<script type="text/javascript">
var _set_param = {w:<?php echo $thumb['width']; ?>,h:<?php echo $thumb['height']; ?>,x:0,y:0,fileid:<?php echo $fileid; ?>,app:'<?php echo $app; ?>'};
var _orig_size = {w:<?php echo $origsize['w']; ?>,h:<?php echo $origsize['h']; ?>};
var _fixed_size = {w:<?php echo $fixedsize['w']; ?>,h:<?php echo $fixedsize['h']; ?>};
var _min_size = {w:<?php echo $thumb['width']; ?>,h:<?php echo $thumb['height']; ?>};
function selectionEnd(img, selection)
{
    var selRate = _orig_size.w / _fixed_size.w;
    _set_param.w = Math.round(selection.width * selRate);
    _set_param.h = Math.round(selection.height * selRate);
    _set_param.x = Math.round(selection.x1 * selRate);
    _set_param.y = Math.round(selection.y1 * selRate);
    var rate = selection.width / <?php echo $thumb['width']; ?>;
    var w = Math.round(_fixed_size.w / rate);
    var h = Math.round(_fixed_size.h / rate);
    var l = Math.round(selection.x1 / rate);
    var t = Math.round(selection.y1 / rate);
    jQuery("#previewImg").css("width", w).css("height", h).css("left",-l).css("top",-t);
}

function confirmCut()
{
    if (_set_param.w < <?php echo $thumb['width']; ?> || _set_param.h < <?php echo $thumb['height']; ?>) {
        alert("<?php echo $i18n['imageSelectedTooSmall']; ?>");
        return false;
    }
    jQuery("#messageDiv").show();
    jQuery.ajax({
        url: '<?php echo TMConfig::get("base_url"); ?>singleupload/upload/docut',
        type: 'POST',
        data: _set_param,
        dataType: 'json',
        timeout: 180000,
        error: function(request, type, ex){
            jQuery("#messageDiv").hide();
        },
        success: function(data){
            if (!data.success) {
                alert(data.message);
                jQuery("#messageDiv").hide();
            } else {
                parent.<?php echo $callbackFunName; ?>(data);
            }
           }
       });
}

jQuery(document).ready(function(){
    var selRate = _orig_size.w / _fixed_size.w;
    _min_size.w = Math.round(<?php echo $thumb['width']; ?> / selRate);
    _min_size.h = Math.round(<?php echo $thumb['height']; ?> / selRate);
    setTimeout(function(){
        jQuery('#areaSelectImg').imgAreaSelect({
            aspectRatio: "<?php echo $thumb['width']; ?>:<?php echo $thumb['height']; ?>",
            selectionColor: 'white',
            onSelectEnd: selectionEnd,
            resizable: true,
            x1:0,
            y1:0,
            x2:_min_size.w,
            y2:_min_size.h,
            show:true,
            minWidth:_min_size.w,
            minHeight:_min_size.h,
            noNewSelect:true
        });
    },1000);
});
</script>
<?php } ?>
</body>
</html>