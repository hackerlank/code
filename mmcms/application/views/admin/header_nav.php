<div class="header">
<h1><a href="#"></a></h1>
<div class="top_menu"> 登录者：<strong><?php echo $this->session->userdata('adminname');?></strong> <a href="/admin/logout" class="login_out">[退出]</a> <i>|</i> 日期：<?php echo date("Y-m-d");?> <i>|</i><a href="" class="tpm_a_1">后台首页</a> <i>|</i><a href="#" class="tpm_a_2">前台首页</a></div>
</div>
<div class="nav" style="display:none;">
	<ul>
    	<li class="nav_ico_1"><a href="#">系统管理</a></li>
        <li class="nav_ico_2 current"><a href="#">客户管理</a></li>
        <li class="nav_ico_3"><a href="#">商品管理</a></li>
        <li class="nav_ico_4"><a href="#">订单管理</a></li>
        <li class="nav_ico_5"><a href="#">促销管理</a></li>
        <li class="nav_ico_6"><a href="#">内容管理</a></li>
        <li class="nav_ico_7"><a href="#">客户反馈</a></li>
        <li class="nav_ico_8"><a href="#">报表统计</a></li>
        <li class="nav_ico_9"><a href="#">其他工具</a></li>
    </ul>
</div>
<div class="operate" style="display:none;">
        	<div class="newsbox">
            	<span>系统公告</span>
                <i>|</i>
                <span>新供货系统顺利上线啦！</span>
            </div>
        	<ul class="pannel">
            	<li><a href="#"><span class="icon_forward"></span>前进</a></li>
            	<li><a href="#"><span class="icon_back"></span>后退</a></li>
            	<li><a href="#"><span class="icon_refresh"></span>刷新</a></li>
            	<li><a href="#"><span class="icon_faq"></span>FAQ</a></li>
            	<li><a href="#"><span class="icon_pw"></span>密码</a></li>
            	<li><a href="#"><span class="icon_exit"></span>退出</a></li>
            </ul>
        </div>