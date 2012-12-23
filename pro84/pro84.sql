CREATE TABLE IF NOT EXISTS `adminuser` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(20) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;



CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL auto_increment,
  `useraccount` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `atype` int(11) NOT NULL default '0',
  `type` int(11) NOT NULL default '0',
  `status` tinyint(4) NOT NULL,
  `imgurl` varchar(255) NOT NULL default '0',
  `time` datetime NOT NULL,
  `date` date NOT NULL,
  `show_start_date` date NOT NULL,
  `show_end_date` date NOT NULL,
  `show_area` varchar(255) NOT NULL,
  `show_link` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;



CREATE TABLE IF NOT EXISTS `articlestype` (
  `id` int(11) NOT NULL auto_increment,
  `typename` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `ordernum` int(11) NOT NULL,
  `pid` int(11) NOT NULL default '0',
  `type` int(11) NOT NULL default '0',
  `template` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `typename` (`typename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;



CREATE TABLE IF NOT EXISTS `goods_attr` (
  `id` int(11) NOT NULL auto_increment,
  `flag` varchar(50) NOT NULL,
  `val` varchar(50) NOT NULL,
  `memo` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `flag` (`flag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;


CREATE TABLE IF NOT EXISTS `goods_attr_info` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL,
  `atype` varchar(50) NOT NULL,
  `val` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `goods_img` (
  `id` int(11) NOT NULL auto_increment,
  `gid` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `goods_info` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL COMMENT '商品名',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `author_type` varchar(20) NOT NULL COMMENT '作者分类',
  `author_title` varchar(50) NOT NULL COMMENT '职称',
  `standard` text NOT NULL COMMENT '规格',
  `craft` varchar(20) NOT NULL COMMENT '工艺',
  `theme` varchar(20) NOT NULL COMMENT '题材',
  `age` varchar(20) NOT NULL COMMENT '创造时间',
  `time` datetime NOT NULL COMMENT '上架时间',
  `price` varchar(50) NOT NULL COMMENT '价格区间',
  `brief` text NOT NULL COMMENT '简介',
  `goods_type` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `thumb_img` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;



CREATE TABLE IF NOT EXISTS `goods_type` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(80) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

ALTER TABLE  `goods_img` ADD  `thumbpath` VARCHAR( 255 ) NOT NULL;
