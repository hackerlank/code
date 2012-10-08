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
function userLogin()
{
    var modalDialogId = "logindialogdiv";
    var modaldiv = '<div class="modal fade" id="'+modalDialogId+'" style="display:none;">'+
        '<div class="modal-header">'+
        '<a class="close" onclick="javascript:closeModalDialog(\''+modalDialogId+'\');">x</a>'+
        '<h3><i class="icon-user"></i> 用户登录</h3></div>'+
        '<div class="modal-body">'+
        '<div class="form-horizontal"><filedset>'+
        '<div class="control-group"><label for="user_name" class="control-label">用户名</label> <div class="controls"><input type="text" name="user_name" id="user_name" value="" /> </div></div>'+
        '<div class="control-group"><label for="user_pwd" class="control-label">密  码</label><div class="controls"><input type="password" name="user_pwd" id="user_pwd" value="" /></div></div>'+
        '</filedset></div>'+
        '</div>'+
        '<div class="modal-footer"><button class="btn-large btn-primary"> &nbsp;&nbsp;登&nbsp;&nbsp;录 &nbsp;&nbsp;</button></div>'+
        '</div>';
    var bgdiv = '<div class="modal-backdrop fade in"></div>';
    $("body").append(modaldiv).append(bgdiv);
    $("#"+modalDialogId).show().addClass("in");
}
