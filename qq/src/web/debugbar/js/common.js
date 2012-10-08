function hideDebugBar(){
	var toolbar = document.getElementById("divDebugbarBody");
	var ctlimg = document.getElementById('imgDbCCtrl');
	var pTbCtrl = document.getElementById('pDbCtrl');
	if(toolbar.style.display=='none'){
		//toolbar.style.display = 'block';
		ctlimg.className = 'icon_toolbar_up';
		pTbCtrl.title = '隐藏工具条';
		jQuery('#divDebugbarBody').show();
		//show('divToolbarBody');
	}
	else{
		//toolbar.style.display = 'none';
		ctlimg.className = 'icon_toolbar_down';
		pTbCtrl.title = '显示工具条';
		jQuery('#divDebugbarBody').hide();
		//closeed('divToolbarBody');
	}
}

function get_child_nodes(obj){
    var nodes = [], obj = document.getElementById(obj);
	if(!obj){return false}
    for (var i = 0; i < obj.childNodes.length; i++) {
        if (obj.childNodes[i].nodeName != '#text' && obj.childNodes[i].nodeName != '#comment') {
            nodes.push(obj.childNodes[i]);
        }
    }
    return nodes;
}
function toggle(pid,aid){
	var p_obj = document.getElementById(pid);
	var a_obj = document.getElementById(aid);
	a_obj.style.display="block";
	p_obj.className = 'quick_links_1';
	get_child_nodes(aid)[0].onmouseover = function(){
		a_obj.style.display="block";
		p_obj.className = 'quick_links_1';
	}
	get_child_nodes(aid)[0].onmouseout = function(){
		a_obj.style.display="none";
		p_obj.className = 'quick_links';
	}
	p_obj.onmouseout = function(){
		a_obj.style.display="none";
		p_obj.className = 'quick_links';
	}
}

function toggle_menu4(pid,aid){
	var p_obj = document.getElementById(pid);
	var a_obj = document.getElementById(aid);
	a_obj.style.display="block";
	p_obj.className = 'quick_links gr';
	get_child_nodes(aid)[0].onmouseover = function(){
		a_obj.style.display="block";
		p_obj.className = 'quick_links gr';
	}
	get_child_nodes(aid)[0].onmouseout = function(){
		a_obj.style.display="none";
		p_obj.className = 'quick_links gr';
	}
	p_obj.onmouseout = function(){
		a_obj.style.display="none";
		p_obj.className = 'quick_links gr';
	}
} 

function stopB(e){
	if(!e)e=window.event;
	e.cancelBubble=true;
}

var st = '';
function sm(event)
{
	clearTimeout(st);
	eventSrc=event.srcElement||event.target;
	o=document.getElementById('divQLinksMenu4');
	if(eventSrc.tagName=="li"){
		tt=setTimeout(function(){
			o.style.display="block";
			document.onmousemove=cpp;
		},1000);
	}
	else {
		clearTimeout(st);
		o.style.display="block";
		document.onmousemove=cpp;
	}
}
function cpp(event)
{
	evt=window.event?window.event:event;
	var pointer=function(event){
		return{x:event.pageX||(event.clientX+
			(document.documentElement.scrollLeft||document.body.scrollLeft)),y:event.pageY||(event.clientY+
			(document.documentElement.scrollTop||document.body.scrollTop))};
	}(evt);
	l=o.offsetLeft+560;
	t=o.offsetTop-25;
	r=l+o.offsetWidth;
	b=t+o.offsetHeight+20;
	py=pointer.y;
	px=pointer.x;
	//alert(l+' '+r+' '+' '+t+' '+b+' '+px+' '+py);
	if((py>t && py<b && px>l && px<r)){}
	else{
		st=setTimeout(function(){
			o.style.display="none";
		},1000);
		document.onmousemove=function(){};
	}
}

window.onresize = setState;
/**
 * 调整状态栏
 */
function setState()
{
	if(document.documentElement.clientWidth<950 )
	{
		if(document.getElementById('divTbQLinksArea4') == null)
		{
			return;
		}
		document.getElementById('divTbQLinksArea4').style.display="none";
		if(document.all){
			document.getElementById('divTbAdvertise').style.display="none";
		}
	}
	else
	{
		if(document.getElementById('divTbQLinksArea4') == null)
		{
			return;
		}
		document.getElementById('divTbQLinksArea4').style.display="block";
		if(document.all){
			document.getElementById('divTbAdvertise').style.display="block";
		}
	}
}

trac_image = new Image();
function clkbtn(tid){
	var trac_url = "http://jump.t.l.qq.com/ping?target=http%3A//giuseppe.act.qq.com&cpid=" + qbar_actid + "&type=" + tid;
	trac_image.src = trac_url;
}

function OpenWin()
{
	var llogin = document.getElementById('linka');
    if (document.all) {
    	llogin.click();
    }
    else
    {
    	var href = $('#linka').attr('href');
    	var target = $('#linka').attr('target');
		window.open(href, target);
    }
}