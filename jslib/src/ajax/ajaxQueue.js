/**
 * @fileOverview app jslib AJAX队列类
 * @author bondli@tencent.com
 * @copyright Copyright (c) 2010-2011 tencent Inc. All rights reserved.
 * @version 1.4.2
 */
$app.ajaxQueue = {
	/**
	 * 队列数组
	 */
	_queueArr : [],
	
	/**
	 * 当前执行的AJAX对象索引
	 */
	_nowIndex : 0,
	
	/**
	 * AJAX队列中数目
	 */
	_ajaxCount : 0,
	
	/**
	 * @name _addQueue
	 * @description 将要执行的AJAX逐条的加载AJAX对象中
	 * @param {Object} JsonObj ajax的json对象
	 * @author bondli@tencent.com
	 * 
	 */
	_addQueue : function (JsonObj){
		this._queueArr.push(JsonObj);
		this._ajaxCount++;
	},
	
		
	/**
	 * @name _runAjax
	 * @description 执行当前索引的AJAX对象
	 * @param {Object} JsonObj AJAX对象
	 * @author bondli@tencent.com
	 * 
	 */
	_runAjax : function (JsonObj){
		var url = JsonObj.url;
		var sucHandler = JsonObj.success;
		var type = JsonObj.type;
		loader('ajax/ajax',function(){
			$app.ajax.get(url,function(e){
				sucHandler(e);
				$app.ajaxQueue.callback();
			},type);
		});
	},
	
	/**
	 * @name run
	 * @description 开始执行AJAX队列
	 * @param {Object} queueArr AJAX队列的JSON对象
	 * @author bondli@tencent.com
	 * 
	 */
	run : function (queueArr){
		for(i in queueArr){
			this._addQueue(queueArr[i]);
		}
		this._nowIndex = 0;
		this.callback();
	},
	
	/**
	 * @name callback
	 * @description 执行AJAX后回调函数
	 * @author bondli@tencent.com
	 * 
	 */
	callback : function (){
		if (this._nowIndex >= this._ajaxCount) return;
		var JsonObj = this._queueArr[this._nowIndex];
		this._runAjax(JsonObj);
		this._nowIndex++;
	}
};