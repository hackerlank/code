document.domain = "qq.com";

jQuery.fn.extend({
	fileupload : function(options) {
		var size = undefined == options.size ? 1 : options.size;
		var filelist = undefined == options.filelist ? '' : options.filelist;

		this.attr('href', 'javascript:void(0);');
		this.after('<input id="file_url" name="file_url" type="hidden">');
		this.after('<div id="file_list"></div>');
		this.after('<div id="fileupload_filelist" filelist="' + filelist + '"></div>');
		this.after('<div id="fileupload_callback_params" count="0" size="' + size + '" style="display:none;"></div>');
		this.after(' <img id="loading_img" src="/components/fileupload/images/loading.gif" width="16" height="16" style="vertical-align:middle;display:none">');
		this.click(function(){
			// 判断是否已经超出文件个数限制
			var count = parseInt($('form #fileupload_callback_params').attr('count'));
			if (count >= size){
				alert('您已经选择了' + count + '个文件。');
				return false;
			}
			
			// 判断用户是否登录
			if (!$app.auth.isLogin()) {
				$app.auth.login();
				return false;
			}
			
			getTicket();		
		});
		$("html").append(fileuploadFormHTML(options.callback));
		$("#file_upload_form #file").change(function() {
			$('#file_upload_form').submit();
		});
	}
});

function fileuploadFormHTML(callback) {
	var fileuploadUrl = 'http://upload.act.qq.com/cgi-bin/up_pic';
	var html = '<div id="fileupload_component">';
	var callbackFn = undefined == callback ? 'fileuploadFormCallback' : callback;
	
	html += '<form id="file_upload_form" action="' + fileuploadUrl + '" method="post" enctype="MULTIPART/FORM-DATA" target="if">';
	html += '<input id="uin" name="uin" type="hidden" value="' + '"/>';
	html += '<input id="actid" name="actid" type="hidden" value="' + '"/>';
	html += '<input id="ticket" name="ticket" type="hidden"/>';
	html += '<input id="callbackName" name="callbackName" type="hidden" value="' + callbackFn + '"/>';
	html += '<input id="file" name="fileField" type="file" style="display:none"/></form>';
	html += '<iframe id="if" name="if" src="about:blank" frameborder="0" style="display:none;"></iframe>';
	html += '</div>'; 
	return html;
}

// 默认 Form 表单递交回调函数
function fileuploadFormCallback(result){
	var retcode = result['body.retcode'];
	
	if (0 == retcode) {
		var size = $('form #fileupload_callback_params').attr('size');
		var count = parseInt($('form #fileupload_callback_params').attr('count'));
		var filelist = $("form #fileupload_filelist").attr('filelist');
			
		// 单一文件上传
		if (1 == size){
			var html = '<input name="fileupload_fileid" value="' + result['body.fileid'] + '">' ;
			html += '<input name="fileupload_local_url" value="' + result['body.local_url'] + '">';
			html += '<input name="fileupload_store_url" value="' + result['body.store_url'] + '">';
			html += '<input name="fileupload_verify_code" value="' + result['body.verify_code'] + '">';
			$("form #fileupload_callback_params").append(html);
			
		} else {
			// 多文件上传
			var html = '<input name="fileupload_fileid[]" value="' + result['body.fileid'] + '">' ;
			html += '<input name="fileupload_local_url[]" value="' + result['body.local_url'] + '">';
			html += '<input name="fileupload_store_url[]" value="' + result['body.store_url'] + '">';
			html += '<input name="fileupload_verify_code[]" value="' + result['body.verify_code'] + '">';
			

		}
	
		// 文件列表
		switch (filelist){
			case 'image':
				addImageHTML(getThumbURL(result['body.store_url'], 100), count);
				break;
			case 'none':
				break;
			default:
				$("form #fileupload_filelist").append('<span>' + $('#file_upload_form #file').val() + '</span>; ');
		}
		
		$("form #fileupload_callback_params").append(html);		
		
		count += 1;
		$('form #fileupload_callback_params').attr('count', count);
	} else {
		alert(result['body.rspmsg']);
	}
}

function addImageHTML(url, index){
	$("form #loading_img").show();
	setTimeout(function(){
		$("form #fileupload_filelist").append('<li><img src="' + url + '"></li>');
		$("form #loading_img").hide();
		}, 1500);
}

function getThumbURL(url, num){
	if(num == 100 || num == 200 || num == 400 || num ==800)
		return url.replace(/\/0$/, '/' + num);
	return url;
}

function getTicket() {
	var url = '/fileupload/fileupload/ticket?' + Date.parse(new Date());
	var result = false;

	$.ajax({
		type : 'GET',
		url : url,
		dataType : 'json',
		success : function(data) {
			switch (data.code) {
			case 0:
				$("#file_upload_form #ticket").val(data.data.ticket);
				$("#file_upload_form #uin").val($app.auth.getQQNum());
				$("#file_upload_form #actid").val(appConfig.tamsid);
				$("#file_upload_form #file").click();
				break;
			case 10102:
				$app.auth.login();
				break;
			default :
				alert(data.message);
				break;
			}
		}
	});

	return false;
}

function getFlashUploadUrl() {
	if (!$app.auth.isLogin()) {
		$app.auth.login();
		return false;
	}

	var url = "/fileupload/fileupload/ticket";
	var result = ajaxCall(url, null);
	
	if (0 == result.code) {
		ticket = result.data.ticket;
		picUrl = "http://upload.act.qq.com/cgi-bin/up_pic_flash?uin=" + uin
				+ '&actid=' + actid + '&ticket=' + ticket;
	} else {
		alert(result.message);
		return false;
	}
	
	return false;
}

// POST URL 请求
function ajaxCall(url, data) {
	var timestamp = Date.parse(new Date());
	var toUrl = url + '/?' + timestamp;
	var result = false;

	$.ajax({
		type : 'POST',
		url : toUrl,
		data : data,
		async : false,
		cache : false,
		dataType : 'json',
		success : function(result) {
			data = result;
		},
		error : function() {
			alert('系统繁忙');
		}
	});

	return data;
}