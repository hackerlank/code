$birth = {
		init : function(year,month,day){
			//年份初始化
			var year_options = "<option value=''>请选择</option>";
			for(var i = 2010; i >= 1949; i--){
				year_options += "<option value='"+i+"'>"+i+"</option>";
			}
			$("#"+year).append(year_options);
			//为年份变动绑定事件
			var month_options = "<option value=''>请选择</option>";
			$("#"+year).change(function(){
				//初始化月份
				for (var i = 1; i <= 12; i++){
					month_options += "<option value='"+i+"'>"+i+"</option>";
				}
				$("#"+month).children().remove();
				$("#"+month).append(month_options);
			});
			//为月份变动绑定事件
			$("#"+month).change(function(){
				//初始化日期
				var _year = $("#"+year).val();//年份
				var _month = $("#"+month).val();//月份
				var _day = 30;//日期
				if (_month == 1 || _month == 3 || _month == 5 || _month == 7 || _month == 8 || _month == 10 || _month == 12){
					_day = 31;
				} else if(_month == 2){
					if(( _year % 4 == 0 && _year % 100 != 0) || ( _year % 100 == 0 ) ) {_day = 29;} else {_day = 28;}
				} else {
					_day = 30;
				}
				var day_options = "<option value=''>请选择</option>";
				for (var i = 1; i <= _day; i++){
					day_options += "<option value='"+i+"'>"+i+"</option>";
				}
				$("#"+day).children().remove();
				$("#"+day).append(day_options);
			});
		}
}