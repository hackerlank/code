#DROP DATABASE IF EXISTS `Vendor_DB_Laneige2011`;
CREATE DATABASE `Vendor_DB_Laneige2011` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE `Vendor_DB_Laneige2011`;
set names utf8;

##用户活动信息表
#DROP TABLE IF EXISTS `Tbl_User`;
CREATE TABLE `Tbl_User` (
    `FUserId` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
    `FQQ` VARCHAR(16) NOT NULL COMMENT '用户QQ号码',
    `FNick` VARCHAR(255) NOT NULL COMMENT '用户昵称',
    `FPwd` VARCHAR(255) NOT NULL COMMENT '用户密码',
    `FScoreCount` INT NOT NULL COMMENT '用户积分总分',
    `FVoteCount` INT NOT NULL COMMENT '用户总得票数',
    `FInviteCount` INT NOT NULL COMMENT '邀请好友数',
    `FBType` INT NOT NULL COMMENT '自定义使用',
    `FLType` INT NOT NULL COMMENT '自定义使用',
    `FEnable` TINYINT UNSIGNED NOT NULL COMMENT '用户审核状态 1：默认 2：通过 3：审核不通过 4：推荐（审核系统）',
    `FValue1` INT NOT NULL COMMENT '备用字段1',
    `FValue2` INT NOT NULL COMMENT '备用字段2',
    `FValue3` VARCHAR(255) NOT NULL COMMENT '备用字段3',
    `FValue4` VARCHAR(255) NOT NULL COMMENT '备用字段4',
    `FValue5` TEXT NOT NULL COMMENT '备用字段5',
    `FValue6` TEXT NOT NULL COMMENT '备用字段6',
    `FTime` DATETIME NOT NULL DEFAULT '00-00-00 00:00:00' COMMENT '加入时间',
    `FDate` DATE NOT NULL DEFAULT '00-00-00' COMMENT '加入日期',
    `FIp` VARCHAR(16) NOT NULL DEFAULT '' COMMENT '加入IP',
    `FLastLoginTime` DATETIME NOT NULL DEFAULT '00-00-00 00:00:00' COMMENT '最近一次登录的时间',
    `FLastLoginDate` DATE NOT NULL DEFAULT '00-00-00' COMMENT '最近一次登录的日期',
    `FMemo` VARCHAR(255) NOT NULL COMMENT '备注',
    PRIMARY KEY (`FUserId`),
    UNIQUE INDEX `INDEX_FQQ` (`FQQ`) #用于按QQ查询用户
    #INDEX `INDEX_FSCORECOUNT` (`FScoreCount`), #用于按用户积分对用户列表进行排序
    #INDEX `INDEX_FTIME` (`FTime`), #用于按加入时间对用户列表进行排序
    #INDEX `INDEX_FDATE` (`FDate`) #用于按日期查询用户列表
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

##用户详细资料表
#DROP TABLE IF EXISTS `Tbl_UserProfile`;
CREATE TABLE `Tbl_UserProfile` (
    `FUserProfileId` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户资料ID',
    `FQQ` VARCHAR(16) NOT NULL COMMENT '用户QQ号码',
    `FUserId` INT UNSIGNED NOT NULL COMMENT '用户Id，对应Tbl_User.FUserId',
    `FTrueName` VARCHAR(255) NOT NULL COMMENT '名字',
    `FNick` VARCHAR(255) NOT NULL COMMENT '用户昵称',
    `FSex` VARCHAR(8) NOT NULL COMMENT '用户性别',
    `FAge` VARCHAR(8) NOT NULL COMMENT '用户年龄',
    `FBirthday` VARCHAR(32) NOT NULL COMMENT '用户生日',
    `FHeight` VARCHAR(8) NOT NULL COMMENT '用户身高',
    `FWeight` VARCHAR(8) NOT NULL COMMENT '用户体重',
    `FDegree` VARCHAR(32) NOT NULL COMMENT '用户学历',
    `FProvince` VARCHAR(32) NOT NULL COMMENT '用户所在省份',
    `FCity` VARCHAR(32) NOT NULL COMMENT '用户所在城市',
    `FAddr` TEXT NOT NULL COMMENT '用户详细地址',
    `FZipCode` VARCHAR(8) NOT NULL COMMENT '用户邮政编码',
    `FTel` VARCHAR(32) NOT NULL COMMENT '电话',
    `FMobile` VARCHAR(32) NOT NULL COMMENT '用户手机',
    `FEmail` VARCHAR(255) NOT NULL COMMENT '用户E-mail',
    `FIdcard` VARCHAR(64) NOT NULL COMMENT '身份证号',
    `FBloodType` VARCHAR(8) NOT NULL COMMENT '用户血型',
    `FCareer` VARCHAR(255) NOT NULL COMMENT '用户职业',
    `FCollege` VARCHAR(255) NOT NULL COMMENT '毕业院校',
    `FHomePage` VARCHAR(255) NOT NULL COMMENT '用户主页',
    `FFavorite` VARCHAR(255) NOT NULL COMMENT '用户爱好',
    `FPersonalDesc` TEXT NOT NULL COMMENT '用户个人说明',
    `FTime` DATETIME NOT NULL DEFAULT '00-00-00 00:00:00' COMMENT '用户填写资料的时间',
    `FDate` DATE NOT NULL DEFAULT '00-00-00' COMMENT '用户填写资料的日期',
    PRIMARY KEY (`FUserProfileId`),
    UNIQUE INDEX `INDEX_FQQ` (`FQQ`)              #用于按QQ查询用户
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

##作品表
#DROP TABLE IF EXISTS `Tbl_File`;
CREATE TABLE `Tbl_File`(
    `FFileId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `FQQ` VARCHAR(16) NOT NULL COMMENT '作者QQ',
    `FUserId` VARCHAR(255) NOT NULL COMMENT '作者用户ID Tbl_User.FUserId',
    `FUser` VARCHAR(255) NOT NULL COMMENT '作者帐号（冗余）',
    `FName` VARCHAR(255) NOT NULL COMMENT '作者名(冗余)',
    `FNick` VARCHAR(255) NOT NULL COMMENT '作者昵称(冗余)',
    `FType` INT NOT NULL COMMENT '作品类别',
    `FVoteCount` INT NOT NULL COMMENT '投票数',
    `FViewCount` INT NOT NULL COMMENT '展示次数',
    `FScore` INT NOT NULL COMMENT '作品积分',
    `FCheckTime` DATETIME NOT NULL DEFAULT '00-00-00 00:00:00' COMMENT '审核时间',
    `FEnable` TINYINT UNSIGNED NOT NULL COMMENT '是否已审核##审核状态 1、未审核（默认值） 2、审核通过 3、审核不通过 4、优秀作品',
    `FFileName` VARCHAR(255) NOT NULL COMMENT '作品名',
    `FText` TEXT DEFAULT NULL COMMENT '文章',
    `FUrl` VARCHAR(255) NOT NULL COMMENT '图片视频路径',
    `FMiniUrl` VARCHAR(255) NOT NULL COMMENT '缩略图路径',
    `FDesc` VARCHAR(255) NOT NULL COMMENT '作品描述',
    `FTime` DATETIME NOT NULL DEFAULT '00-00-00 00:00:00' COMMENT '上传时间',
    `FDate` DATE NOT NULL DEFAULT '00-00-00' COMMENT '上传日期',
    `FMemo` VARCHAR(64) NOT NULL COMMENT '备注',
    `FState` TINYINT UNSIGNED NOT NULL COMMENT '状态(是否已经转换)',
    `FDealTime` DATETIME NOT NULL DEFAULT '00-00-00 00:00:00' COMMENT '处理时间(转换时间)',
    `FAudioUrl` VARCHAR(255) NOT NULL default '' COMMENT '音频URL',
    `FVideoUrl` VARCHAR(255) NOT NULL default '' COMMENT '视频URL',
    `FVideoMiniUrl` VARCHAR(255) NOT NULL default '' COMMENT '视频缩略图URL',
    PRIMARY KEY (`FFileId`),
    INDEX `INDEX_FQQ`(`FQQ`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

#邀请记录表
#DROP TABLE IF EXISTS `Tbl_InviteHistory`;
CREATE TABLE IF NOT EXISTS `Tbl_InviteHistory` (
  `FInviteHistoryId` int(10) unsigned NOT NULL AUTO_INCREMENT,  #邀请好友明细表的id
  `FSrcQQ` varchar(16) NOT NULL,                                #邀请人的QQ
  `FSrcId` int(10) NOT NULL,                                    #邀请人的作品id（仅使用在作品邀请中）
  `FDesQQ` varchar(16) NOT NULL,                                #被邀请人的QQ
  `FTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',      #时间
  `FDate` date NOT NULL DEFAULT '0000-00-00',                   #日期
  `FMemo` varchar(255) NOT NULL DEFAULT '',                     #备注
  PRIMARY KEY (`FInviteHistoryId`),
  KEY `INDEX_FSRCQQ` (`FSrcQQ`),
  KEY `INDEX_FDESCQQ` (`FDesQQ`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

##加分明细表
#DROP TABLE IF EXISTS `Tbl_ScoreDetail`;
CREATE TABLE `Tbl_ScoreDetail` (
     `FScoreDetailId`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
     `FDesId`               INT UNSIGNED NOT NULL COMMENT '被加分者的Id(针对作品)',
     `FDesQQ`               VARCHAR(16) NOT NULL COMMENT '被加分者的QQ(针对个人)',
     `FSrcId`               INT UNSIGNED NOT NULL COMMENT '加分者的Id',
     `FSrcQQ`               VARCHAR(16) NOT NULL COMMENT '加分者的QQ',
     `FStrategy`            VARCHAR(64) NOT NULL COMMENT '加分类型',
     `FScore`               INT NOT NULL DEFAULT 0 COMMENT '分数细节',
     `FStatus`              TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0:未操作1:操作失败2：操作成功',
     `FIp`                  VARCHAR(32) NOT NULL COMMENT 'ip',
     `FDesUserKey`          VARCHAR(255) NOT NULL COMMENT '被加分用户的Key',
     `FTime`                DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL COMMENT '加分时间',
     `FDate`                DATE DEFAULT '0000-00-00' NOT NULL COMMENT '加分日期',
     `FMemo`                VARCHAR(255) NOT NULL COMMENT '备注，可用于记录为什么加分',
     PRIMARY KEY (`FScoreDetailId`),
     KEY `INDEX_FDESQQ_FSTRATEGY` (`FDesQQ`, `FStrategy`(8))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='加分明细表';


##兑换码数据表
#DROP TABLE IF EXISTS `Tbl_Code`;
CREATE TABLE `Tbl_Code`
(
    `FId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `FQQ` VARCHAR(32) NOT NULL, ##用户QQ
    `FCode` VARCHAR(255)  NOT NULL,   ##兑换码
    `FType` INT UNSIGNED NOT NULL,            ##兑换码类别
    `FStatus` INT UNSIGNED DEFAULT 0 NOT NULL, ##是否使用，0为否，1为是
    `FApplyTime` DATETIME  DEFAULT '0000-00-00 00:00:00' NOT NULL, ##兑换时间
    `FMemo` VARCHAR(64 ) DEFAULT NULL,              ##备注  
    PRIMARY KEY (FId),
    INDEX (FCode)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;


##用户评论表
#DROP TABLE IF EXISTS `Tbl_Comment`;
CREATE TABLE `Tbl_Comment` (
    `FCommentId` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录id',
    `FDesId` VARCHAR(16) NOT NULL COMMENT '被评论人的QQ或作品ID',
    `FSreId` VARCHAR(16) NOT NULL COMMENT '评论人的QQ，用于兼容老的TAMS，请把值设置为FSrcId一致',
    `FSrcId` VARCHAR(16) NOT NULL COMMENT '评论人的QQ（源）',
    `FNick` VARCHAR(255) NOT NULL COMMENT '评论者昵称',
    `FTitle` VARCHAR(255) NOT NULL COMMENT '评论标题',
    `FComment` TEXT NOT NULL COMMENT '评论内容',
    `FType1` INT NOT NULL COMMENT '自定义',
    `FType2` INT NOT NULL COMMENT '自定义',
    `FFaceIconId` INT NOT NULL COMMENT '评论表情id',
    `FTime` DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL COMMENT '评论时间',
    `FDate` DATE NOT NULL DEFAULT '0000-00-00' COMMENT '评论日期 比如：2010-03-31',
    `FEnable` TINYINT UNSIGNED DEFAULT '0' NOT NULL COMMENT '审核状态 1、未审核（默认值） 2、审核通过 3、审核不通过',
    `FCheckTime` DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL COMMENT '被审核时间',
    `FMemo` VARCHAR(255) DEFAULT NULL COMMENT '评论备注',
    PRIMARY KEY (`FCommentId`),  ##表主键
    INDEX `INDEX_FDESID` (`FDesId`), ##用户id索引
    INDEX `INDEX_FSRCID` (`FSrcId`)  ##作品id索引
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

#投票历史表
#DROP TABLE IF EXISTS `Tbl_VoteHistory`;
CREATE TABLE `Tbl_VoteHistory` (
     `FVoteHistoryId`       INT UNSIGNED NOT NULL AUTO_INCREMENT,                           ##ID
     `FStrategy`            INT UNSIGNED NOT NULL COMMENT '投票类型，1: 普通，2：手机',
     `FType`                INT UNSIGNED NOT NULL COMMENT '投票类型，1: 普通，2：手机',
     `FDesQQ`               VARCHAR(16) NOT NULL COMMENT '被投票者的QQ',                    ##被投票者的QQ
     `FDesId`               INT UNSIGNED NOT NULL COMMENT '被投票作品的ID',
     `FSrcQQ`               VARCHAR(16) NOT NULL COMMENT '投票者的QQ号,如果是手机投票则为投票者的手机',
     `FSrcId`               INT UNSIGNED NOT NULL COMMENT '投票者的ID',
     `FUserkey`             VARCHAR(255) NOT NULL COMMENT '',
     `FIp`                  VARCHAR(32) NOT NULL COMMENT '投票者IP',                        ##投票者IP
     `FVoteCounts`          VARCHAR(8) NOT NULL COMMENT '投票票数，一般为1',                ##投票票数 1
     `FTime`                DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '投票时间（默认写入当前时间）',    ##投票时间
     `FDate`                DATE NOT NULL DEFAULT '0000-00-00' COMMENT '投票日期',                                     ##投票日期
     `FMemo`                VARCHAR(255) DEFAULT NULL COMMENT '备注',
     PRIMARY KEY (`FVoteHistoryId`),
     INDEX `INDEX_FSRCQQ` (`FSrcQQ`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='投票历史表';

##积分信息表
#DROP TABLE IF EXISTS `Tbl_Score`;
CREATE TABLE `Tbl_Score` (
    `FScoreId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `FQQ` VARCHAR(16) NOT NULL,
    `FUser` VARCHAR(32) NOT NULL COMMENT '昵称',
    `FUserId` INT NOT NULL,
    `FScore` INT NOT NULL DEFAULT 0,
    `FScore1` INT NOT NULL DEFAULT 0 COMMENT '共10类加分 可根据自身业务需要 自行定义',
    `FScore2` INT NOT NULL DEFAULT 0,
    `FScore3` INT NOT NULL DEFAULT 0,
    `FScore4` INT NOT NULL DEFAULT 0,
    `FScore5` INT NOT NULL DEFAULT 0,
    `FScore6` INT NOT NULL DEFAULT 0,
    `FScore7` INT NOT NULL DEFAULT 0,
    `FScore8` INT NOT NULL DEFAULT 0,
    `FScore9` INT NOT NULL DEFAULT 0,
    `FScore10` INT NOT NULL DEFAULT 0,
    `FTime` DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
    `FDate` DATE NOT NULL DEFAULT '0000-00-00' COMMENT '日期 比如：2010-03-31',
    `FMemo` VARCHAR(255) NOT NULL,
    PRIMARY  KEY (`FScoreId`),
    UNIQUE INDEX `INDEX_FQQ` (`FQQ`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

#目前组件使用的抽奖表
#DROP TABLE IF EXISTS `Tbl_LotteryHistory`;
CREATE TABLE `Tbl_LotteryHistory` (
  `FLotteryHistoryId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FQQ`             varchar(16) NOT NULL DEFAULT '',                    #抽奖者QQ
  `FType`           varchar(32) NOT NULL DEFAULT '',                    #实际返回的奖品类型
  `FGroup`          varchar(32) NOT NULL DEFAULT '',                    #实际返回的奖品组
  `FCode`           int not null default 0,                         #实际返回的奖品代码
  `FName`           varchar(255) NOT NULL DEFAULT '',                   #实际返回的奖品名
  `FValue`          varchar(255) NOT NULL DEFAULT '',                   #实际返回的奖品的值
  `FInnerError`     varchar(64) NOT NULL DEFAULT '',                    #内部错误代码
  `FCodeOrig`       tinyint not null default 0,                         #用户抽到的奖品代码
  `FTime`           datetime NOT NULL DEFAULT '0000-00-00 00:00:00',    #抽奖时间
  `FDate`           date NOT NULL,                                      #抽奖日期
  `FIp`             varchar(32) NOT NULL,                               #抽奖用户IP
  `FMemo`           varchar(255) DEFAULT NULL,                          #备注
  PRIMARY KEY (`FLotteryHistoryId`),
  KEY `INDEX_FCODE_FDATE` (`FCode`,`FDate`),
  KEY `INDEX_FQQ` (`FQQ`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

#DROP TABLE IF EXISTS `Tbl_LotteryCount`;
CREATE TABLE `Tbl_LotteryCount` (
  `FLotteryCountId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FKey`            varchar(32) NOT NULL DEFAULT '', 
  `FCount`          int unsigned NOT NULL DEFAULT 1,
  `FMemo`           varchar(255) DEFAULT NULL,
  PRIMARY KEY (`FLotteryCountId`),
  UNIQUE KEY `INDEX_FKEY` (`FKey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# QQ虚拟物品信息表
#DROP TABLE IF EXISTS `Tbl_QQshow`;
CREATE TABLE `Tbl_QQshow` (
     `FQQshowId`        INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
     `FStatus`          TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送状态',
     `FQQ`              VARCHAR(16) NOT NULL COMMENT '获得者QQ',
     `FItemNo`          VARCHAR(32) NOT NULL COMMENT '物品Itemno',
     `FActId`           VARCHAR(64) NOT NULL COMMENT '营销平台活动号',
     `FApplyTime`       DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '申请时间',
     `FDealTime`        DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '处理时间',
     `FCode`            VARCHAR(32) NOT NULL COMMENT '物品编码',
     `FSouceUser`       VARCHAR(32) NOT NULL COMMENT '',
     `FTime`            DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '赠送给用户的时间',
     `FDate`            DATE NOT NULL DEFAULT '0000-00-00' COMMENT '获赠给用户的日期',
     `FMemo`            VARCHAR(255) NOT NULL COMMENT '备注     ',
     PRIMARY KEY (`FQQshowId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='QQ虚拟物品信息表';