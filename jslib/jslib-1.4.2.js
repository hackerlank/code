/**
 * 项目配置
 */
 var appConfig = {
      namespace: '$',						//命名空间,ready函数就是用到这个了，其他地方均不用这个
      runMode:   'test',					//定义开发模式,dev为外包模式，test为测试环境，production为线上模式
      domain:    'http://jslib.qq.com/',				//定义项目域名，外包也是需要设置是qq.com结尾的域名
      rootPath:  'http://jslib.qq.com/',//'http://appmedia.qq.com/media/jslib/',		//jslib所在的访问路径
      appid:     4005706,					//定义项目所用到的APPID
      tamsid:    641009031,					//定义项目的活动ID，这个和监测PV/UV相关

      //以下配置修改请谨慎
      autoload: ['core','speed/mo2','auth/auth','utils/util','verifycode/verifycode']
 };

//邀请链接，这里定义了toolbar中会读取这里的
appConfig.inviteUrl = appConfig.domain;
//投票的后台地址
appConfig.ajaxVoteUrl = appConfig.domain+'jslib/serv/ajaxVote.html';
//兑换的后台地址
appConfig.ajaxExchangeUrl = appConfig.domain+'jslib/serv/exchange.html';
//抽奖的后台地址
appConfig.ajaxLotteryUrl = appConfig.domain+'jslib/serv/lottery.html';

//定义页面加载完成后的Filter函数
var _onPageReadyFilter = function() {
	//页面PV/UV监测
	$app.util.track();
	//toolbar的加载
	$app.util.toolbar();
};

//载入加载器，加载autoload 
var subpath = (appConfig.runMode == 'production') ? 'build' : 'src';
document.write("<scri" + "pt type=\"text/javascript\" src=\""+ appConfig.rootPath + subpath +"/loader.js\"></scri" + "pt>");