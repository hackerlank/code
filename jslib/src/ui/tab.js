/**
 * @fileOverview app jslib UI类标签切换控制组件
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 * 
 * 注意：必须约定tab的id值格式为tab_i，内容标签的id值格式为panel_i(其中i为数字)。
 *		tab选中时的className约定为on。页面起初加载时必须显示标签1和内容1，即tab_1
 *		的className为on，panel_1的display为block
 *
 * example : 
 *	<ul id='nav'>								------这里的id值'nav'就是所要传递的参数
 *		<li><a id='tab_1'>tab1 title</a></li>	------这里的id值格式必须为"tab_i"
 *		<li><a id='tab_2'>tab2 title</a></li>
 *		<li><a id='tab_3'>tab3 title</a></li>
 *	</ul>
 *	<div id='panel_1'>tab1 content</div>		------这里的id值格式必须为"panel_i"
 *	<div id='panel_2'>tab2 content</div>
 *	<div id='panel_3'>tab3 content</div>
 *	<div id='panel_4'>tab4 content</div>
 */
$app.tab = {
	/**
	 * @name init
	 * @description TAB标签初始化函数
	 * @param {String} tabCID 包含所有tab的ul标签的id值
	 * @author bondli@tencent.com
	 * 
	 */
	init : function(tabCID) {
		var tabL = document.getElementById(tabCID).children.length;
		window.tabOn  = 'tab_1';
		for(var i=1;i<=tabL;i++) {
			//初始化设置
			if(i==1){
				document.getElementById('tab_1').className = 'on';
				document.getElementById('panel_1').style.display = 'block';
			}
			else{
				document.getElementById('tab_'+i).className = '';
				document.getElementById('panel_'+i).style.display = 'none';
			}
			//add listener to tab
			var tab = document.getElementById('tab_'+i);
			tab.style.cursor = 'pointer';
			
			$app.addEvent(tab, 'click', function(e){
				//get event target
				if(e.target) {//ie
					targ = e.target;
				} else if(e.srcElement) {//ff
					targ = e.srcElement;
				}
				if(targ.nodeType == 3) {
					//defeat Safari bug
					targ = targ.parentNode;
				}
				if(targ.id == window.tabOn) {
					//click the same tab
					return;
				}
				//开启点击标签
				targ.className = "on";
				//隐藏旧标签
				document.getElementById(window.tabOn).className='';
				//设置旧标签内容的容器
				var tNum = window.tabOn.substr(targ.id.indexOf('_')+1);
				var dBox = document.getElementById('panel_'+tNum);//获取旧标签内容的容器
				dBox.style.display = 'none';
				//设置点击标签内容的容器
				tNum = targ.id.substr(targ.id.indexOf('_')+1);
				dBox = document.getElementById('panel_'+tNum);//获取点击标签内容的容器
				dBox.style.display = 'block';
				//设置现在打开的标签到全局变量中
				window.tabOn = targ.id;
			});
		}
	}
};