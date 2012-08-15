function showModalDialog(title, msg, width, height)
{
    var modalDialogId = "modaldialogdiv";
    var modelCss = '<style type="text/css">.close{float:right;font-size:20px;font-weight:700;line-height:18px;color:#000;text-shadow:0 1px 0 #fff;opacity:.2;filter:alpha(opacity=20)}.close:hover{color:#000;text-decoration:none;opacity:.4;filter:alpha(opacity=40);cursor:pointer}.modal-open .dropdown-menu{z-index:2050}.modal-open .dropdown.open{*z-index:2050}.modal-open .popover{z-index:2060}.modal-open .tooltip{z-index:2070}.modal-backdrop{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1040;background-color:#000}.modal-backdrop.fade{opacity:0}.modal-backdrop,.modal-backdrop.fade.in{opacity:.8;filter:alpha(opacity=80)}.modal{position:fixed;top:50%;left:50%;z-index:1050;max-height:'+height+'px;overflow:auto;width:'+width+'px;margin:-250px 0 0 -280px;background-color:#fff;border:1px solid #999;border:1px solid rgba(0,0,0,.3);*border:1px solid #999;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;-webkit-box-shadow:0 3px 7px rgba(0,0,0,.3);-moz-box-shadow:0 3px 7px rgba(0,0,0,.3);box-shadow:0 3px 7px rgba(0,0,0,.3);-webkit-background-clip:padding-box;-moz-background-clip:padding-box;background-clip:padding-box}.modal.fade{-webkit-transition:opacity .3s linear,top .3s ease-out;-moz-transition:opacity .3s linear,top .3s ease-out;-ms-transition:opacity .3s linear,top .3s ease-out;-o-transition:opacity .3s linear,top .3s ease-out;transition:opacity .3s linear,top .3s ease-out;top:-25%}.modal.fade.in{top:50%}.modal-header{padding:9px 5px;border-bottom:1px solid #eee}.modal-header .close{margin-top:2px}.modal-body{padding:15px}.modal-body .modal-form{margin-bottom:0}.modal-footer{padding:14px 15px 15px;margin-bottom:0;background-color:#f5f5f5;border-top:1px solid #ddd;-webkit-border-radius:0 0 6px 6px;-moz-border-radius:0 0 6px 6px;border-radius:0 0 6px 6px;-webkit-box-shadow:inset 0 1px 0 #fff;-moz-box-shadow:inset 0 1px 0 #fff;box-shadow:inset 0 1px 0 #fff;*zoom:1}.modal-footer:before,.modal-footer:after{display:table;content:""}.modal-footer:after{clear:both}.modal-footer .btn{float:right;margin-left:5px;margin-bottom:0}</style>';
    $("body").before(modelCss);
    var modaldiv = '<div class="modal fade" id="'+modalDialogId+'" style="display:none;">'+
        '<div class="modal-header">'+
        '<a class="close" onclick="javascript:closeModalDialog();">×</a>'+
        '<h3>'+title+'</h3></div>'+
        '<div class="modal-body">'+msg+'</div>'+
        '<div class="modal-footer">'+
        '<a href="#" class="btn btn-primary" onclick="javascript:closeModalDialog();">关闭</a>'+
        '</div></div>';
    var bgdiv = '<div class="modal-backdrop fade in"></div>';
    $("body").append(modaldiv).append(bgdiv);
    $("#"+modalDialogId).show().addClass("in");
}
function closeModalDialog()
{
    var modalDialogId = "modaldialogdiv";
    $("#"+modalDialogId).show().removeClass("in");
    setTimeout('$("#'+modalDialogId+'").hide();$(".modal-backdrop").remove();$("#'+modalDialogId+'").remove();',300);
}
function showMsg(msg)
{
    var msg = "<center style='color:red;'>"+msg+"</center>";
    showModalDialog("温馨提示", msg, 560, 500);
}
