/**
 * @fileOverview app jslib 表单验证类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.form = {
	/**
	 * @name checkForm
	 * @description 初始化表单验证
	 * @param {String} formid	表单的ID
	 * @param {String} submitType	提交表单的类型
	 * @param {Function} callback	提交后的处理函数，如果提交方式为submit，这个失效
	 * @author bondli@tencent.com
	 * 
	 */
	checkForm : function(formid,submitType,callback){
		var _formobj = $app.G(formid);
		var _inputs = _formobj.getElementsByTagName('input');
		var _selects = _formobj.getElementsByTagName('select');
		var _textareas = _formobj.getElementsByTagName('textarea');
		
		//收集表单对象和数据，用于验证和提交
		var _elements = [];
		for(var i = 0, len = _inputs.length; i < len; i++) {
			_elements[_inputs[i].name] = _inputs[i];
		}
		for(var i = 0, len = _selects.length; i < len; i++) {
			_elements[_selects[i].name] = _selects[i];
		}
		for(var i = 0, len = _textareas.length; i < len; i++) {
			_elements[_textareas[i].name] = _textareas[i];
		}
		
		//为表单提交添加监听事件
		$app.addEvent(_formobj,'submit',function(e){
			//阻止表单提交
			if(document.all){
				e.returnValue = false;	
			}
			else{
				e.preventDefault();
			}
			//开始验证表单
			var msgs = '';
			var data = [];
			for(o in _elements){
				var obj = _elements[o];
				var datatype = obj.getAttribute('datatype');
				var inputvalue = obj.value;
				switch(obj.type) {
					case 'checkbox' : {
						var _tmp = document.getElementsByName(obj.name);
						data[obj.name] = '';
						for(var c = 0, len = _tmp.length; c < len; c++) {
							if(_tmp[c].checked){
								data[obj.name] += _tmp[c].value + ',';
							}
						}
						data[obj.name] = data[obj.name].substring(0,data[obj.name].length-1);
						inputvalue = data[obj.name];
						break;
					}
					case 'radio'    : {
						var _tmp = document.getElementsByName(obj.name);
						for(var t = 0, len = _tmp.length; t < len; t++) {
							if(_tmp[t].checked){
								data[obj.name] = _tmp[t].value;
								inputvalue = _tmp[t].value;
								break;
							}
						}
						if(data[obj.name] == undefined) inputvalue = '';
						break;
					}
					default : {
						data[obj.name] = obj.value;
						break;
					}
				}
				//对有配置验证的字段进行校验
				if(datatype != null && typeof(datatype) != 'undefined'){
					var norequire = false;
					if(datatype.indexOf('norequire')>-1){
						//对配置有非必填的字段做特殊处理
						norequire = true;
						datatype = datatype.replace('norequire ','');
						inputvalue = inputvalue.replace(/^\s*(.*?)\s*$/, "$1");
					}
					var dtArr = datatype.split(' ');
					var flag = false;
					for(var i=0;i<dtArr.length;i++){
						var ret = false;
						if(dtArr[i] == 'unSafe') ret = true;
						if(norequire == true){
							if(inputvalue != ''){
								if($app.form.validatorType[dtArr[i]].test(inputvalue) == ret){
									flag = true;
								}
							}
						}
						else{
							if($app.form.validatorType[dtArr[i]].test(inputvalue) == ret){
								flag = true;
							}
						}
						
					}
					if(flag == true) msgs += obj.getAttribute('msg') + "\n";
				}
			}
			
			//如果出现错误了
			if(msgs.length>0){
				alert(msgs);return false;
			}
			
			//如果验证通过提交表单
			if(submitType.toLowerCase() == 'post'){
				//ajax提交
				var url = _formobj.getAttribute('action');
				$app.form.ajaxSubmit(url,'POST',data,callback);
			}
			else if(submitType.toLowerCase() == 'get'){
				var url = _formobj.getAttribute('action');
				$app.form.ajaxSubmit(url,'GET',data,callback);
			}
			else{
				//普通方式提交
				_formobj.submit();
			}
		});
	},
	
	/**
	 * @description 验证数据类型
	 * @field
	 */
	validatorType : {
		require : /[^(^\s*)|(\s*$)]/,	
		email : /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
		phone : /^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/,
		mobile : /^0{0,1}1[0-9]{10}$/,
		tel : /^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$|^0{0,1}1[0-9]{10}$/,
		currency : /^\d+(\.\d+)?$/,
		number : /^\d+$/,
		zip : /^[0-9]\d{5}$/,
		ip  : /^[\d\.]{7,15}$/,
		idcard : /^\d{17}[0-9Xx]$/,
		qq : /^[1-9]\d{4,9}$/,
		integer : /^[-\+]?\d+$/,
		english : /^[A-Za-z]+$/,
		chinese : /^[\u0391-\uFFE5]+$/,
		userName : /^[A-Za-z0-9_]{3,}$/i,
		unSafe : /[<>\?\#\$\*\&;\\\/\[\]\{\}=\(\)\.\^%,]/
	},
	
	/**
	 * @name ajaxSubmit
	 * @description AJAX方式提交表单
	 * @param {Object} url
	 * @param {Object} data
	 * @param {Object} callback
	 */
	ajaxSubmit : function(url,type,data,callback){
		var datastr = '';
		for(o in data){
			if(o != null && o != '') datastr += '&' + o + '=' + data[o];
		}
		if(datastr.length==0){
			return;
		}
		datastr = datastr.substring(1);
		
		var a = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
		with (a) {
			open(type, url+'?'+datastr, true);
			if(type == 'POST'){
				setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				send(datastr);
			}
			else {
				send(null);
			}
			
			onreadystatechange = function(){
				if (readyState == 4 && status == 200) {
					if(responseText.length > 0){
						eval('var data =' + responseText);
					}
					else{
						var data = '';
					}
					callback(data);
				}
			};
		}
	}
};