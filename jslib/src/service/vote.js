/**
 * @fileOverview app jslib 投票组件
 * @param {Number} vid 被投票的作品ID或者QQ号码
 * @param {Function} callback 投票回调函数 
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.vote = function(vid,callback){
	if(!$app.auth.isLogin()){
		alert('温馨提示：请先登录QQ！');
		$app.auth.login();
		return;
	}
	else{
		loader('verifycode/verifycode',function(){
			var callback = callback || function(rs){
				alert(rs.message);
				if(rs.code != 0){
					return false;
				}
				else{
					var curNum = parseInt($app.G('vote_'+vid+'_count').innerHTML);
					$app.G('vote_'+vid+'_count').innerHTML = curNum + rs.count;
				}
			};
			$app.verifycode.show('请输入验证码', function(data){
				var verifyCode = data.verifycode;
				var vid = data.vid;
				var callback = data.callback;
				loader('ajax/ajax',function(){
					var url = appConfig.ajaxVoteUrl;
					$app.ajax.post(url,{"vid":vid,"verifycode":verifyCode},function(e){
						callback(e);
					},'json');
				});
			},{"vid":vid,"callback":callback});
		});
	}
};
