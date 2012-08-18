// JavaScript Document
//自由式
$(document).ready(function() {
	var sheight=$(document).height()-140;
	$(".aside").css("height",sheight+'px');
	$(".switch").css("height",sheight-1+'px');
	$(".icon_arr_l").css("top","48%");
})
$(".icon_arr_l").click(function(){
	var $switch=$(this).parent();
	if($(this).attr("class")=="icon_arr_l"){
		$switch.prev().css("display","none")
		$switch.parent().css("width","8px");
		$switch.parent().parent().next().css("marginLeft","8px");
		$(this).addClass("icon_arr_r");
	}
	else{
		$switch.prev().css("display","")
		$switch.parent().css("width","198px");
		$switch.parent().parent().next().css("marginLeft","198px");
		$(this).removeClass("icon_arr_r");
	}
})
$(".fed-menu-title").click(function(){
	var $list=$(this).closest(".fed-menu-box").find(".fed-menu-list")
	//if($list.style.display!='none'){
	if($list.css("display")=="none"){	
		$list.show();
		$(this).addClass("tit_on");
	}
	else{
		$list.hide();
		$(this).removeClass("tit_on");
	}
})

/*隔行变色*/
$(function(){
		   $(".order_tb>tbody>tr:odd").addClass("t_bg");
		   });
