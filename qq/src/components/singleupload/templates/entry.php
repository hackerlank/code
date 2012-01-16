<?php if (!empty($css)) { foreach ($css as $cssSrc) {?>
<link type="text/css" rel="stylesheet" href="<?php echo $cssSrc; ?>" />
<?php } } ?>
<?php if (!empty($js)) { foreach ($js as $jsSrc) {?>
<script type="text/javascript" src="<?php echo $jsSrc; ?>"></script>
<?php } } ?>
<script type="text/javascript">
function fileUploadCallBack_<?php echo $app; ?>(fileUploadData) {
    $app.dialog.close();
    setTimeout(function(){        
        if (fileUploadData.success) {
            var options = {
                "message": "<?php echo $i18n['uploadSuccessInfo']; ?>",
                "timeout": 2000,
                "onClosed": function() {
                    <?php if (!empty($onUploadSuccess)) echo 'eval("' . $onUploadSuccess . '(fileUploadData);");'; ?>
                    <?php if (!empty($thumb) && !empty($thumb['cut'])) { ?>showCutPage_<?php echo $app; ?>(fileUploadData);<?php } ?>
                    }
                };
            $app.dialog.show(options);
        } else {
            var options = {
                "message": fileUploadData.message,
                "timeout": 2000,
                "onClosed": function() {showUploadPage_<?php echo $app; ?>();}
                };
            $app.dialog.show(options);
        }
    },400);
}

function showUploadPage_<?php echo $app; ?>() {
    var options = {
        title: "<?php echo $i18n['uploadIframeTitle']; ?>",
        width: 425,
        height: 250,
        modal: false,
        message: '<iframe width="425" height="250" scrolling="no" frameborder="0" src="<?php echo TMConfig::get("base_url"); ?>singleupload/upload/upload?app=<?php echo $app; ?>"></iframe>'
    };
    $app.dialog.show(options);
}

jQuery(document).ready(function(){
    jQuery("<?php echo $entry; ?>").each(function(){
        jQuery(this).click(function(evt){
            evt.preventDefault();
            if (!$app.checkQQLogin()) {
                $app.loginQQ('');
            } else {
                showUploadPage_<?php echo $app; ?>();
            }
        });
    });
});

<?php if (!empty($thumb) && !empty($thumb['cut'])) { ?>
function onCutSuccess_<?php echo $app; ?>(cutResultData) {
    $app.dialog.close();
    <?php if (!empty($thumb) && !empty($thumb['onCutSuccess'])) echo 'eval("' . $thumb['onCutSuccess'] . '(cutResultData);");'; ?>
}

function showCutPage_<?php echo $app; ?>(fileUploadData) {
    var options = {
        title: "<?php echo $i18n['cutIframeTitle']; ?>",
        width: <?php echo $cutinfo['cutIframe']['width']; ?>,
        height: <?php echo $cutinfo['cutIframe']['height']; ?>,
        modal: false,
        message: '<iframe width="<?php echo $cutinfo['cutIframe']['width']; ?>" height="<?php echo $cutinfo['cutIframe']['height']; ?>" scrolling="no" frameborder="0" src="<?php echo TMConfig::get("base_url"); ?>singleupload/upload/cut?app=<?php echo $app; ?>&fileid='+fileUploadData.id+'"></iframe>'
    };
    $app.dialog.show(options);
}
<?php } ?>
</script>