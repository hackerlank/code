/**
 * @fileOverview app jslib 填充生日表单
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.brith = {
	/**
	 * 初始化
	 * 年对应的选择框ID，月对应的选择框ID，日对应的选择框ID，初始年，初始月，初始日，选择一个值时执行的动作
	 */
	init : function(o_year,o_month,o_day,s_year,s_month,s_day,fn) {
		//获得对象
		var o_year = document.getElementById(o_year);
	    var o_month = document.getElementById(o_month);
	    var o_day = document.getElementById(o_day);
	    
	    //如果年份有初始值
	    if(s_year != ''){
	    	//年份初始化
		    var y = 0; var yindex = 0;
		    for (var i=2010; i>=1949; i--) {
		    	o_year.options[y] = new Option(i,i);
		    	if(i == s_year){yindex = y;}
		    	y++;
		    }
	    	//选中当前年
	    	o_year.selectedIndex = yindex;
	    }
	    else{
	    	o_year.options[0] = new Option('请选择','');
	    	//年份初始化
		    var y = 1;
		    for (var i=2010; i>=1949; i--) {
		    	o_year.options[y] = new Option(i,i);y++;
		    }
	    }
	    //如果月份有初始值
	    if(s_month != ''){
	    	//初始化月数据
	    	o_month.options.length = 0;
	    	for (var i=0; i<12; i++) {
		    	o_month.options[i] = new Option(i+1,i+1);
		    }
		    //选中当前月
	    	o_month.selectedIndex = s_month-1;
	    	
	    	//初始化日数据
	    	if(s_month == 1 || s_month == 3 || s_month == 5 || s_month == 7 || s_month == 8 || s_month == 10 || s_month == 12){
				var d = 31;
			}
			else if(s_month ==2){
				if(( s_year % 4 == 0 && s_year % 100 != 0) || ( s_year % 100 == 0 ) ) {var d = 29;} else {var d = 28;}
			}
			else{
				var d = 30;
			}
	    }
	    else{
	    	o_month.options[0] = new Option('请选择','');
	    }
	    //如果日有初始值
	    if(s_day != ''){
	    	//初始化日数据
	    	o_day.options.length = 0;
	    	for (var i=0; i<d; i++) {
		    	o_day.options[i] = new Option(i+1,i+1);
		    }
		    //选中当前日
	    	o_day.selectedIndex = s_day-1;
	    }
	    else{
	    	o_day.options[0] = new Option('请选择','');
	    }
	    //为年份变动绑定事件
	    this.AddEvent(o_year,'change',function(){
	    	var year = o_year.options[o_year.selectedIndex].value;
			var month = o_month.options[o_month.selectedIndex].value;
			if(month == '') {
				//初始化月数据
				y = 1;
		    	for (var i=1; i<=12; i++) {
			    	o_month.options[y] = new Option(i,i);y++;
			    }
				return;
			}
			var d = 30;
			if(month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12){
				d = 31;
			}
			else if(month ==2){
				if(( year % 4 == 0 && year % 100 != 0) || ( year % 100 == 0 ) ) {d = 29;} else {d = 28;}
			}
			else{
				d = 30;
			}
			y = 0;
			o_day.options.length = 0;
			for (var i=1; i<=d; i++) {
				o_day.options[y] = new Option(i,i);y++;
			}
			if(fn) eval(fn+'();');
	    });
	    //为月份变动绑定事件
	    this.AddEvent(o_month,'change',function(){
	    	var year = o_year.options[o_year.selectedIndex].value;
			var month = o_month.options[o_month.selectedIndex].value;
			var d = 30;
			if(month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12){
				d = 31;
			}
			else if(month ==2){
				if(( year % 4 == 0 && year % 100 != 0) || ( year % 100 == 0 ) ) {d = 29;} else {d = 28;}
			}
			else{
				d = 30;
			}
			o_day.options.length = 0;
			y = 0;
			for (var i=1; i<=d; i++) {
				o_day.options[y] = new Option(i,i);y++;
			}
			if(fn) eval(fn+'();');
	    });
	    //为日期变动绑定事件
	    this.AddEvent(o_day,'change',function(){
	    	if(fn) eval(fn+'();');
	    });
	},
	/**
	 * 为对象添加监听事件
	 */
	AddEvent : function( obj, type, fn ) {
		if(typeof(obj) == 'string'){
			obj = document.getElementById(obj);	
		}
        if (obj.addEventListener) {
			obj.addEventListener(type, fn, false);
		}
        else if (obj.attachEvent){
			obj.attachEvent('on' + type, function() { 
				return fn.apply(obj, new Array(window.event));
			});
		}
	}
};