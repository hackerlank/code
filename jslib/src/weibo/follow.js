/**
 * @fileOverview app jslib 官方微博收听/取消
 * @author yueqiao@tencent.com
 */

$app.follow = {
	/**
	 * 收听地址
	 */
	followUrl: 'http://t.act.qq.com/follow/default/',

	/**
	 * 取消收听地址
	 */
	cancelUrl: 'http://t.act.qq.com/unfollow/default/',

	/**
	 * 默认的回调函数，给出收听（取消收听）后的返回消息
	 */
	_default: function(r){
		alert(r.msg);
	},
	
	/**
	 * 收听
	 * @param {String} id 需要收听的微博帐号
	 */
	listen: function( id, callback ) {
		var url = this.followUrl + '?u=' + encodeURIComponent(id);
		callback = callback || this._default;
		loader('ajax/ajax',function(){
			$app.ajax.getJSONP( url, callback );
		});
	},

	/**
	 * 取消收听
	 * @param {String} id 需要取消收听的微博帐号
	 */
	cancel: function( id, callback ) {
		var url = this.cancelUrl + '?u=' + encodeURIComponent(id);
		callback = callback || this._default;
		loader('ajax/ajax',function(){
			$app.ajax.getJSONP( url, callback );
		});
	}

};
