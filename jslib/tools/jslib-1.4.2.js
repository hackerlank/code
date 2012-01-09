﻿/**
 * 项目配置
 */
 var appConfig = {
      namespace: '$',						//命名空间,ready函数就是用到这个了，其他地方均不用这个
      runMode:   'production',					//定义开发模式,dev为外包模式，test为测试环境，production为线上模式
      domain:    'http://lf.qq.com/',				//定义项目域名，外包也是需要设置是qq.com结尾的域名
      rootPath:  'http://appmedia.qq.com/media/jslib/',		//jslib所在的访问路径
      appid:     4008401,					//定义项目所用到的APPID
      tamsid:    641009031,					//定义项目的活动ID，这个和监测PV/UV相关

      //以下配置修改请谨慎
      autoload: ['core','speed/mo2','auth/auth','utils/util','verifycode/verifycode']
 };

//邀请链接，这里定义了toolbar中会读取这里的
appConfig.inviteUrl = appConfig.domain;
//投票的后台地址
appConfig.ajaxVoteUrl = appConfig.domain+'vote/vote/save';
//兑换的后台地址
appConfig.ajaxExchangeUrl = appConfig.domain+'exchange/exchnage/save';

//载入加载器，加载autoload 
var subpath = (appConfig.runMode == 'production') ? 'build' : 'src';
document.write("<scri" + "pt type=\"text/javascript\" src=\""+ appConfig.rootPath + subpath +"/loader.js\"></scri" + "pt>");