<script src="debugbar/js/jquery.js"></script>
<script src="debugbar/js/common.js"></script>
<link rel="stylesheet" type="text/css" href="debugbar/css/style.css">
<style>
.quick_links_list .inner {
    margin-top: 2px;
}
.debugbar .wrap a:hover{
text-decoration:none;
}
</style>
<script type="text/javascript">
var qbar_actid = <?php echo TMConfig::get("tams_id"); ?>;

function show(id)
{
	jQuery(".quick_links_list").hide();
	jQuery("#"+id).show();
}

function close(id)
{
    jQuery("#"+id).hide();
}

function getDebugBarAjaxInfo()
{
	jQuery.ajax({
	    type: "POST",
	    url: "/taedebug/showajaxbar",
	    data: {},
	    success: function(data){
            if(data != "")
            {
                jQuery("#divDebugbarRoot").html(data);
            }
	    }
	}); 
}

function selectUri()
{
	var uri = jQuery("#selectUri").val();
	jQuery(".debug_panel_right").hide();
	jQuery("#"+uri).show();
}

jQuery().ready(
    function(){
    	getDebugBarAjaxInfo();
    	setInterval("getDebugBarAjaxInfo();", 10000);
    }
);
</script>
<div id="divDebugbarRoot" class="debugbar">
  
</div>