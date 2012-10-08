/**
 * @fileOverview app jslib 变量调试类
 * @param {String} variable 变量名称
 * @param {Boolean} isWindow 是否弹出窗
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.debug = function(variable,isWindow){
	var tp = typeof(variable);
    var str = '';
    var alertArr = {
        'string': 1,
        'number': 1,
        'boolean': 1,
        'undefined': 1
    };
    var debugTag;
    if (alertArr[tp] || tp == 'function' || !!variable == false) {
        str = '' + tp + " " + variable;
    }
    else if (tp == "object" || tp == "array" || tp == 'class' || tp == 'arguments') {
		for (var p in variable) {
			str += typeof(variable[p]) + ' ' + p + "=" + variable[p] + "\n";
		}
    }
	else if (tp == "element") {
		str = "element:\n";
		for (var i in variable) {
			str += '' + typeof(variable[i]) + ' ' + i + '=' + variable[i] + "\n";
		}
    }
	else {
		str = 'type of:' + tp + ":" + variable;
    }
    str = str.replace(/&/g, "&amp;");
    str = str.replace(/</g, "&lt;");
    str = str.replace(/>/g, "&gt;");
    if (isWindow == true) {
		try {
			debugWindow = window.open('about:blank', '$app.debug', 'width=800,height=600,scrollbars=1,resizable,status');
			debugWindow.document.write('<html><head><title>$app.debug output</title></head><body><h2>$app.debug Output</h2><div id="debugTag"></div></body></html>');
			debugTag = debugWindow.document.getElementById('debugTag');
			debugTag.innerHTML = ('<b>' + (new Date()).toString() + '</b>:<pre>' + str + '</pre><hr/>') + debugTag.innerHTML;
		}
		catch(e){
			alert("$app.debug Output:\n" + str);
		}
    } 
    else {
        alert("$app.debug Output:\n" + str);
    }
};
