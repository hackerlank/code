<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
    <title>juse test</title>
    <script src="../lib/jquery/jquery-1.4.2.js" type="text/javascript"></script>
    <script src="../lib/form.js" type="text/javascript"></script>
    <script type="text/javascript">
    function test_form_callback(data){
        console.log(data);
        alert(data.msg);
    }
    $(function(){
        $("#test_submit").click(function(){
            $form.check("test_form",test_form_callback);
        });
    });
    </script>
    </head>
     <body>
    <form id="test_form">
    <ul>
    <li><input class="needcheck" datatype="require" des="checkbox_test" type="checkbox" name="checkbox_test" value="a" />A<input type="checkbox" name="checkbox_test" value="b" />B</li>
    <li>
    		<select name="select_test" class="needcheck" datatype="require" des="select_test">
			<option value="">请选择</option>
			<option value="aa">aa</option>
			<option value="bb">bb</option>
			</select>
    </li>
    <li><textarea name="area_test" datatype="require" des="test" class="needcheck"></textarea></li>
    <li><input type="radio" value="A" class="needcheck" name="itype" datatype="require" des="AB" />A<input class="needcheck" type="radio" value="B" name="itype" datatype="require" des="AB" />B</li>
    	<li><span>姓名：</span><input name="name_test" type="text" datatype="require" des="姓名" class="needcheck" /></li>
    	<li><span>电话号码：</span><input name="tel" type="mobile" datatype="mobile" des="电话号码" class="needcheck" /></li>
    	<li><span>邮箱：</span><input name="email" type="text" datatype="email" des="邮箱" /></li>
    	
    </ul>
    <input type="button" value="提交" id="test_submit" />
    </form>
   
    </body>
</html>