/**
 * @fileOverview app jslib 兑换组件
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.exchange = {
	/**
	 * 通过兑换码兑换积分或者奖品
	 */
	code: function(exCode, callback){
		if(!$app.auth.isLogin()){
			alert('温馨提示：请先登录QQ！');
			$app.auth.login();
			return;
		}
		else{
			if($app.util.trim(exCode)==''){
				alert('温馨提示：请输入兑换码！');
				return;
			}
			else{
				loader('verifycode/verifycode',function(){
					var callback = callback || $app.exchange._callback;
					$app.verifycode.show('请输入验证码', function(data){
						var verifyCode = data.verifycode;
						var exCode = data.exCode;
						var callback = data.callback;
						loader('dialog/dialog',function(){
                            $app.dialog.show({
                    			title: '温馨提示',
                    			content: '兑换中，请稍后...',
                    			width: 400,
                    			height: 100,
                    			mask: true,
                    			callback: ''
                    		});
    						loader('ajax/ajax',function(){
    							var url = appConfig.ajaxExchangeUrl;
    							$app.ajax.post(url,{"exCode":exCode,"verifycode":verifyCode},function(e){
    								$app.dialog.close();
    								callback(e);
    							},'json');
    						});
						});
					},{"excode":exCode,"callback":callback});
				});
			}
		}
	},
	
	/**
	 * 通过积分兑换奖品
	 */
	score: function(id, callback){
		if(!$app.auth.isLogin()){
			alert('温馨提示：请先登录QQ！');
			$app.auth.login();
			return;
		}
		else{
			if(parseInt(id)==0){
				alert('温馨提示：请先选择需要兑换的物品！');
				return;
			}
			else{
				loader('verifycode/verifycode',function(){
					var callback = callback || $app.exchange._callback;
					$app.verifycode.show('请输入验证码', function(data){
						var verifyCode = data.verifycode;
						var prizeid = data.prizeid;
						var callback = data.callback;
						loader('dialog/dialog',function(){
                            $app.dialog.show({
                    			title: '温馨提示',
                    			content: '兑换中，请稍后...',
                    			width: 400,
                    			height: 100,
                    			mask: true,
                    			callback: ''
                    		});
    						loader('ajax/ajax',function(){
    							var url = appConfig.ajaxExchangeUrl;
    							$app.ajax.post(url,{"prizeid":prizeid,"verifycode":verifyCode},function(e){
    								$app.dialog.close();
    								callback(e);
    							},'json');
    						});
						});
					},{"prizeid":id,"callback":callback});
				});
			}
		}
	},

	/**
	 * 系统自带回调函数
	 */
	_callback: function(rs){
		alert(rs.message);
		if(rs.code != 0){
			return false;
		}
		else{
			return true;
		}
	}
};