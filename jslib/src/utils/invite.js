/**
 * @fileOverview app jslib 邀请链接类
 * @param {String} url 最终跳回的URL
 * @param {Object} param URL后面的参数键值对对象
 * @param {Boolean} isAlert 是否弹出提示框，默认是弹出
 * @param {String} append 邀请链接后面追加的文字信息
 * @param {Boolean} isBefore 追加文字是否追加在url前面
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.invite = function(url,loginQQ,isAlert,append,isBefore){
	var txt = 'http://jump.t.l.qq.com/ping?target='+escape(url)+'&cpid='+appConfig.tamsid+'&type=1&fromqq='+loginQQ;
	if(append != null && append != ''){
		if(isBefore == true){
			txt = append + ' ' + txt;
		}
		else{
			txt += ' ' + append;
		}
	}
	if(window.clipboardData) {
		window.clipboardData.clearData();
		window.clipboardData.setData("Text", txt);
	} else if(navigator.userAgent.indexOf("Opera") != -1) {
		alert('你的浏览器不支持复制，请用IE或者Firefox来完成复制!');return;
	} else if (window.netscape) {
		try {
			netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		} catch (e) {
			alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");
		}
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		if (!clip) return;
		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		if (!trans) return;
		trans.addDataFlavor('text/unicode');
		var str = new Object(); var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		var copytext = txt; str.data = copytext;
		trans.setTransferData("text/unicode",str,copytext.length*2);
		var clipid = Components.interfaces.nsIClipboard;
		if (!clip) return false;
		clip.setData(trans,null,clipid.kGlobalClipboard);
	}
	else{
		alert('你的浏览器不支持复制，请用IE或者Firefox来完成复制!');return;
	}
	if(isAlert == null || isAlert == true) alert("地址已经复制到您的剪贴板，您可以发送给您的朋友啦!");
};
