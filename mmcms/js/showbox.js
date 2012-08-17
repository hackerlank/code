/*
 * base on bootstrap.css jquery.js
 */
jsex = {};
jsex.dialog = {
		title: '温馨提示',
		msgdiv: 'myModal',
		showmsg: function(msg,title){
			if (''==title || undefined==title) title="温馨提示";
			var modaldiv = '<div class="modal fade" id="'+this.msgdiv+'" style="display:none;">'+
						   '<div class="modal-header">'+
						   '<a class="close" data-dismiss="modal" onclick="jsex.dialog.closemsg();">×</a>'+
						   '<h3>'+title+'</h3></div>'+
						   '<div class="modal-body">'+msg+'</div>'+
						   '<div class="modal-footer">'+
						   '<a href="#" class="btn btn-primary" onclick="jsex.dialog.closemsg();">关闭</a>'+
						   '</div></div>';
			var bgdiv = '<div class="modal-backdrop fade in"></div>';
			$("body").append(modaldiv).append(bgdiv);
			
			$("#"+this.msgdiv).show().addClass("in");
		},
		closemsg: function(){
			$("#"+this.msgdiv).show().removeClass("in");
			setTimeout('$("#'+this.msgdiv+'").hide();$(".modal-backdrop").remove();$("#'+this.msgdiv+'").remove();',300);
		}
}
