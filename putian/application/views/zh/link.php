<?php
/**
 *@author:shenjian@ztgame.com
 *@encoding=UTF-8 ts=4 sw=4
 *@create:2012-4-4
 */
?>
<?php require_once 'head.php';?>
<div class="wp clearfix">
    <div class="pro-l">
        <img src="/images/img-1.jpg" style=" width:220px; margin-top:10px;" />
    </div>
    <div class="pro-r">
    	<h4 class="page-cate"><span></span></h4>
    	<h3 class="news-title"><?php echo $info['title'];?></h3>
		<div class="news-detail"><?php echo $info['content'];?></div>
    </div>
    <div>
        <ul>
            <li>
                <label>(*)姓名：</label>
                <input type="text" name="name" />
            </li>
            <li>
                <label>(*)电话：</label>
                <input type="tel" name="tel" />
            </li>
            <li>
                <label>邮箱：</label>
                <input type="text" name="email" />
            </li>
            <li>
                <label>(*)内容：</label>
                <textarea name="content"></textarea>
            </li>
            <li><input type="button" value="提交" onclick="saveGuestBook();" ></li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    function saveGuestBook()
    {
        var name = $("input[name='name']").val();
        var tel = $("input[name='tel']").val();
        var email = $("input[name='email']").val();
        var content = $("textarea[name='content']").val();

        if(name == '' || tel == '' || content == ''){
            alert("请填写完整信息");
            return false;
        }
        $.post("/page/saveguestbook", {'name': name, "tel": tel, "email": email, "content": content}, function(data){
            alert(data['msg']);
            if(!data['err']){
                $("input[name='name']").val("");
                $("input[name='tel']").val("");
                $("input[name='email']").val("");
                $("textarea[name='content']").val("");
            }

        }, "json");
    }
</script>
<?php require_once 'foot.php';?>
