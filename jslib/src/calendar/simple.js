/**
 * @fileOverview app jslib 日历控件
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
function L_calendar(){
	
};

L_calendar.prototype = {
	newName: "",
	clickObject: null,
	inputObject: null,
	inputDate: null,
	
	//定义年的变量的初始值
	L_TheYear: new Date().getFullYear(),
	
	//定义月的变量的初始值
	L_TheMonth: new Date().getMonth()+1,
	
	//定义写日期的数组
	L_WDay: new Array(42),
	
	//定义阳历中每个月的最大天数
	monHead: new Array(31,28,31,30,31,30,31,31,30,31,30,31),
	
	/**
	 * 获得当前鼠标所在的Y方向位置
	 */ 		   
	getY: function(){
		var obj;
		if (arguments.length > 0){
			obj=arguments[0];
		}
		else{
			obj = this.clickObject;
		}
		if(obj!=null){
			var y = obj.offsetTop;
			while (obj = obj.offsetParent) y += obj.offsetTop;
			return y;
		}
		else{return 0;}
	},
	
	/**
	 * 获得当前鼠标所在的X方向位置
	 */
	getX: function(){
		var obj;
		if (arguments.length > 0){
			obj=arguments[0];
		}
		else{
			obj=this.clickObject;
		}
		if(obj!=null){
			var y = obj.offsetLeft;
			while (obj = obj.offsetParent) y += obj.offsetLeft;
			return y;}
			else{return 0;}
	},
	
	/**
	 * 创建日历的HTML内容
	 */
	createHTML: function(){
		var htmlstr="";
		htmlstr+="<div id=\"L_calendar\">\r\n";
		htmlstr+="<div id=\"L_calendar-year-month\">";
		htmlstr+="<div id=\"L_calendar-PrevM\" onclick=\""+this.newName+".prevM()\" title=\"前一月\">&lt;</div>";
		htmlstr+="<div id=\"L_calendar-year\"></div>";
		htmlstr+="<div id=\"L_calendar-month\"></div>";
		htmlstr+="<div id=\"L_calendar-NextM\" onclick=\""+this.newName+".nextM()\" title=\"后一月\">&gt;</div>";
		htmlstr+="</div>\r\n";
		htmlstr+="<div id=\"L_calendar-day\">\r\n";
		htmlstr+="<ul>\r\n";
		for(var i=0;i<this.L_WDay.length;i++){
			htmlstr+="<li id=\"L_calendar-day_"+i+"\" style=\"background:#8DB2E3\" onmouseover=\"this.style.background='#8DB2E3'\"  onmouseout=\"this.style.background='#FFFFFF'\"></li>\r\n";
		}
		htmlstr+="</ul>\r\n";
		htmlstr+="</div>\r\n";
		htmlstr+="</div>\r\n";

		document.getElementById('L_DateLayer').innerHTML = htmlstr;
		document.getElementById('L_DateLayer').style.display = 'block';
	},
	
	/**
	 * 往指定的ID中写入html内容
	 * @param {String} id		DOM对象的ID名
	 * @param {String} htmlstr	HTML内容
	 */
	insertHTML: function(id,htmlstr){
		document.getElementById(id).innerHTML = htmlstr;
	},
	
	/**
	 * 往日历控件 head 中写入当前的年与月
	 * @param {Object} yy
	 * @param {Object} mm
	 */
	writeHead: function (yy,mm){
		this.insertHTML("L_calendar-year",yy + " 年");
		this.insertHTML("L_calendar-month",mm + " 月");
	},
	
	/**
	 * 判断是否闰平年
	 * @param {Object} year
	 */
	isPinYear: function(year){
		if (0==year%4&&((year%100!=0)||(year%400==0))) return true;else return false;
	},
	
	/**
	 * 获得指定年月的天数
	 * @param {Object} year
	 * @param {Object} month
	 */
	getMonthCount: function(year,month){
		var c = this.monHead[month-1];
		if((month==2)&&this.isPinYear(year)) c++;
		return c;
	},
	
	/**
	 * 往前翻月份
	 */
	prevM: function(){
		if(this.L_TheMonth>1){this.L_TheMonth--}else{this.L_TheYear--;this.L_TheMonth=12;}
		this.setDay(this.L_TheYear,this.L_TheMonth);
	},
	
	/**
	 * 往后翻月份
	 */
	nextM: function(){
		if(this.L_TheMonth==12){this.L_TheYear++;this.L_TheMonth=1}else{this.L_TheMonth++}
		this.setDay(this.L_TheYear,this.L_TheMonth);
	},
	
	/**
	 * 设置日期
	 * @param {Object} yy
	 * @param {Object} mm
	 */
	setDay: function(yy,mm){
		this.writeHead(yy,mm);
		//设置当前年月的公共变量为传入值
		this.L_TheYear = yy;
		this.L_TheMonth = mm;
		//将显示框的内容全部清空
		for (var i = 0; i < 42; i++){this.L_WDay[i]=""};
		//某月第一天的星期几
		var day1 = 1,day2=1,firstday = new Date(yy,mm-1,1).getDay();
		for (i=0;i<firstday;i++)this.L_WDay[i]=this.getMonthCount(mm==1?yy-1:yy,mm==1?12:mm-1)-firstday+i+1;	//上个月的最后几天
		for (i = firstday; day1 < this.getMonthCount(yy,mm)+1; i++){this.L_WDay[i]=day1;day1++;}
		for (i=firstday+this.getMonthCount(yy,mm);i<42;i++){this.L_WDay[i]=day2;day2++}
		for (i = 0; i < 42; i++)
		{
			var da = document.getElementById("L_calendar-day_"+i+"");
			var month,day;
			if (this.L_WDay[i]!="")
			{
				if(i<firstday){
					da.innerHTML = "<span style=\"color:gray\">" + this.L_WDay[i] + "</span>";
					month = (mm==1?12:mm-1);
					day = this.L_WDay[i];
				}
				else if(i>=firstday+this.getMonthCount(yy,mm)){
					da.innerHTML = "<span style=\"color:gray\">" + this.L_WDay[i] + "</span>";
					month = (mm==1?12:mm+1);
					day = this.L_WDay[i];
				}
				else
				{
					da.innerHTML = "<b style=\"color:#000\">" + this.L_WDay[i] + "</b>";
					month=(mm==1?12:mm);
					day = this.L_WDay[i];
				}
				if(document.all){
					da.onclick = Function(this.newName+".dayClick("+month+","+day+")");
				}
				else{
					da.setAttribute("onclick",this.newName+".dayClick("+month+","+day+")");
				}
				da.title=month+" 月"+day+" 日";
				da.style.background="#FFFFFF";
				if(this.inputDate!=null){
					if(yy==this.inputDate.getFullYear() && month== this.inputDate.getMonth() + 1 && day==this.inputDate.getDate()){
						da.style.background="#8DB2E3";
					}
				}
				else{
					da.style.background=(yy == new Date().getFullYear()&&month==new Date().getMonth()+1&&day==new Date().getDate())? "#8DB2E3":"#FFFFFF";
				}
			}
		}
	},
	
	/**
	 * 点击日历选取日期
	 * @param {Object} mm
	 * @param {Object} dd
	 */
	dayClick: function(mm,dd){
		var yy = this.L_TheYear;
		//判断月份，并进行对应的处理
		if(mm<1){yy--; mm = 12+mm;}
		else if(mm>12){yy++; mm = mm-12;}
		if (mm < 10){mm = "0" + mm;}
		if (this.clickObject)
		{
			if (!dd) {return;}
			if ( dd < 10){dd = "0" + dd;}
			this.inputObject.value= yy + "-" + mm + "-" + dd ; //注：在这里你可以输出改成你想要的格式
			this.closeLayer();
		}
		else {
			this.closeLayer(); alert("您所要输出的控件对象并不存在！");
		}
	},
	
	/**
	 * 显示日历控件
	 */
	setDate: function(){
		if (arguments.length < 1 || arguments.length > 2){alert("传入参数错误！");return;}
		this.inputObject = arguments[0];
		this.clickObject = arguments[0];
		var reg = /^(\d+)-(\d{1,2})-(\d{1,2})$/;
		if(arguments.length==2){
			var r = arguments[1].match(reg);
		}
		else{
			var r = this.inputObject.value.match(reg);
		}
		
		if(r!=null){
			r[2]=r[2]-1;
			var d= new Date(r[1], r[2],r[3]);
			if(d.getFullYear()==r[1] && d.getMonth()==r[2] && d.getDate()==r[3]){
				this.inputDate=d;		//保存外部传入的日期
			}
			else this.inputDate = "";

			this.L_TheYear = r[1];
			this.L_TheMonth = r[2]+1;
		}
		else{
			this.L_TheYear = new Date().getFullYear();
			this.L_TheMonth = new Date().getMonth() + 1
		}
		this.createHTML();
		var top = this.getY();
		var left = this.getX();
		var DateLayer = document.getElementById("L_DateLayer");
		DateLayer.style.top = top+this.clickObject.clientHeight+5+"px";
		DateLayer.style.left = left+"px";
		DateLayer.style.display = "block";
		if(document.all){
			document.getElementById("L_calendar").style.width="128px";
			document.getElementById("L_calendar").style.height="130px"
		}
		else{
			document.getElementById("L_calendar").style.width="126px";
			document.getElementById("L_calendar").style.height="130px"
			DateLayer.style.width="126px";
			DateLayer.style.height="130px";
		}
		//alert(DateLayer.style.display)
		this.setDay(this.L_TheYear,this.L_TheMonth);
	},
	
	/**
	 * 关闭日历控件
	 */
	closeLayer: function(){
		try{
			var DateLayer = document.getElementById("L_DateLayer");
			if(arguments[0].id == 'L_calendar-PrevM' || arguments[0].id == 'L_calendar-NextM'){
				DateLayer.style.display="block";
			}
			else if((DateLayer.style.display=="" || DateLayer.style.display=="block") && arguments[0]!=this.ClickObject && arguments[0]!=this.inputObject){
				DateLayer.style.display="none";
			}
		}
		catch(e){}
	}
}

/**
 * 将日志控件先加载到页面上
 */
$app.css(appConfig.rootPath + 'src/calendar/simple.css');
var mainDiv = document.createElement('div');
mainDiv.id="L_DateLayer";
mainDiv.style.display="none";
mainDiv.style.position="absolute";
mainDiv.style.width="128px";
mainDiv.style.height="128px";
mainDiv.style.zIndex="999";
document.body.appendChild(mainDiv);

/**
 * 初始化日历对象
 */
$app.calendar = new L_calendar();
$app.calendar.newName = "$app.calendar";
/**
 * 页面点击事件捕获
 * @param {Object} e
 */
document.onclick = function(e){
	e = window.event || e;
	var srcElement = e.srcElement || e.target;
	$app.calendar.closeLayer(srcElement);
}
