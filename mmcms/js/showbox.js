function showModalDialog(title, msg, width, height, closeBtn)
{
    var modalDialogId = "modaldialogdiv";
    var closeHeadStr = '';
    var closeFootStr = '';
    if (closeBtn) {
        closeHeadStr = '<a class="close" onclick="javascript:closeModalDialog(\''+modalDialogId+'\');">x</a>';
        closeFootStr = '<div class="modal-footer"><a href="javascript:;" class="btn btn-primary" onclick="javascript:closeModalDialog(\"'+modalDialogId+'\");">关闭</a></div>';
    }
    var modaldiv = '<div class="modal fade" id="'+modalDialogId+'" style="display:none;">'+
        '<div class="modal-header">'+closeHeadStr+
        '<h3>'+title+'</h3></div>'+
        '<div class="modal-body">'+msg+'</div>'+
        closeFootStr+
        '</div>';
    var bgdiv = '<div class="modal-backdrop fade in"></div>';
    $("body").append(modaldiv).append(bgdiv);
    $("#"+modalDialogId).show().addClass("in");
}
function closeModalDialog(modalDialogId)
{
    $("#"+modalDialogId).show().removeClass("in");
    setTimeout('$("#'+modalDialogId+'").hide();$(".modal-backdrop").remove();$("#'+modalDialogId+'").remove();',300);
}
function showMsg(msg)
{
    var msg = "<center style='color:red;'>"+msg+"</center>";
    showModalDialog("温馨提示", msg, 560, 500, 1);
}
