/**
 * @fileOverview app jslib Qzone工具类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.qzone = {
	/**
	 * @name sendBlog
	 * @description 发送qzone日志
	 * @param {String} act_id 日志ID
	 * @param {String} callback_FunName 回调函数名称，用于获取返回的数据和处理后台加分或统计的逻辑业务
	 * @author bondli@tencent.com
	 *
	 */
	sendBlog: function(act_id, callback_FunName){
		var isform = document.getElementById('formQZoneBlog');
		var url = 'http://act.qzone.qq.com/user_v3/freereg.php?domain=' + document.domain + '&script&callback=parent.' + callback_FunName + '&act_id=' + act_id;
		if (!isform) {
			var myCont = $app.G('qzoneBCont');
			if (!myCont) {
				var myCont = document.createElement('div');
				myCont.id = 'qzoneBCont';
				myCont.style.display = 'none';
				document.body.appendChild(myCont);
			}
			myCont.innerHTML = '<form id="formQZoneBlog" action="' + url + '" style="display:none" method="post" target="foobarQZoneBlog"><input type="hidden" name="post_blog" value="0" /></form><iframe id="foobarQZoneBlog" name="foobarQZoneBlog" style="display:none;"></iframe>';
		}
		$app.G('formQZoneBlog').submit();
	},
	
	/**
	 * @name sendHang
	 * @description 发送qzone挂件
	 * @param {String} act_id 挂件ID
	 * @param {String} annex 是否挂出，1:自动挂出,0:放在物品栏
	 * @param {String} callback_FunName 回调函数名称，用于获取返回的数据和处理后台加分或统计的逻辑业务
	 * @author bondli@tencent.com
	 *
	 */
	sendHang: function(act_id, annex, callback_FunName){
		var isform = $app.G('formQZoneHang');
		var url = 'http://act.qzone.qq.com/user_v3/freereg.php?domain=' + document.domain + '&script&callback=parent.' + callback_FunName + '&act_id=' + act_id;
		if (!isform) {
			var myCont = $app.G('qzoneHCont');
			if (!myCont) {
				var myCont = document.createElement('div');
				myCont.id = 'qzoneHCont';
				myCont.style.display = 'none';
				document.body.appendChild(myCont);
			}
			myCont.innerHTML = '<form id="formQZoneHang" action="' + url + '" style="display:none" method="post" target="foobarQZoneHang"><input type="hidden" name="hang_annex" value="' + annex + '" /></form><iframe id="foobarQZoneHang" name="foobarQZoneHang" style="display:none;"></iframe>';
		}
		$app.G('formQZoneHang').submit();
	},
	
	/**
	 * @name share
	 * @description 分享到qzone
	 * @param {String} url 要分享的地址
	 * @author bondli@tencent.com
	 *
	 */
	share: function(url){
		var surl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' + encodeURIComponent(url);
		window.open(surl, '_blank');
	},
	
	/**
	 * @name like
	 * @description 加入粉丝榜
	 * @param {String} user 认证空间的号码
	 * @author bondli@tencent.com
	 *
	 */
	like: function(user){
		var frame = $app.G('qzonelike');
		if (!frame) {
			var url = 'http://open.qzone.qq.com/like?url=http%3A%2F%2Fuser.qzone.qq.com%2F' + user + '&type=button_num&width=100&height=30';
			var frame = document.createElement('iframe');
			frame.id = 'qzonelike';
			frame.src = url;
			frame.style.display = 'none';
			document.body.appendChild(frame);
			if (frame.attachEvent){
				frame.attachEvent("onload", function(){
					//alert("Local iframe is now loaded.");
					document.getElementById('qzonelike').contentWindow.QZONE.TC.Like.clickLikeBtn();
				});
			} else {
				frame.onload = function(){
					//alert("Local iframe is now loaded.");
					document.getElementById('qzonelike').contentWindow.QZONE.TC.Like.clickLikeBtn();
				};
			}
		}
		else{
			document.getElementById('qzonelike').contentWindow.QZONE.TC.Like.clickLikeBtn();
		}
	}
}