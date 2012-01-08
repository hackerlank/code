$form = {
		check: function(id,callback){
			//验证表单数据
			var check_error = 0;
			var postdata = {};
			$("#"+id+" .needcheck").each(function(){
				var input_type = $(this).attr("type");
				var data_name = $(this).attr("name");
				var input_value = '';
				switch(input_type){
					case "radio":
						var tmp = $("input[name="+data_name+"]");
						for(var i=0,len = tmp.length; i < len; i++){
							if(i == $("input[name="+data_name+"]:checked").index()){
								postdata[data_name] = $(this).val();
								input_value = $(this).val();
							}
						}
						break;
					case "checkbox":
						var tmp = $("input[name="+data_name+"]");
						postdata[data_name] = '';
						for (var i = 0, len = tmp.length; i < len; i++){
							if(tmp[i].checked){
								postdata[data_name] += tmp[i].value+",";
								input_value += $(this).val();
							}
						}
						postdata[data_name] = postdata[data_name].substring(0,postdata[data_name].length-1);
						break;
					default :
						postdata[data_name] = $(this).val();
						input_value = $(this).val();
						break;
				}
				var data_type = $(this).attr("datatype");
				var data_type_arr = data_type.split(' ');
				for(var i = 0;i<data_type_arr.length;i++){
					if(undefined != data_type_arr[i]){
						var ret = false;
						if(data_type_arr[i] == 'unSafe'){ ret = true;}
						if($form.checkDataType[data_type_arr[i]].test(input_value) == ret){
							console.log(data_type_arr[i]);
							console.log(ret);
							check_error = 1;
							var des = $(this).attr("des");
							var val = $(this).val().replace(/\s+/gm,'');
							if(0 == val.length){
								alert(des+"不能为空，请重新填写后重试！");
							} else {
								if('radio' == input_type || 'checkbox' == input_type){
									alert(des+"为必选项，请选择后重试！");
								} else {
									alert(des+"格式不正确，请重新填写后重试！");
								}
							}
							if('radio' != input_type || 'checkbox' != input_type){
								$(this).val("").focus();
							}
							return false;
						}
					}
					
				}
				
			});
			if(check_error){
				return false;
			}
			//ajax提交数据
			console.log(postdata);
			$.post('/js/test05.php',postdata,callback,'json');
		},
		/**
		 * 验证数据类型
		 */
		checkDataType: {
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
}