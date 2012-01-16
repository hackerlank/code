#兑换历史表
DROP TABLE IF EXISTS `Tbl_ExchangeHistory`;
CREATE TABLE `Tbl_ExchangeHistory` (
    `FId`                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `FQQ`                  VARCHAR(16) NOT NULL,                                       ##兑换者的QQ
    `FIp`                  VARCHAR(32) NOT NULL,                                       ##投票者IP
    `FItem`                TINYINT UNSIGNED NOT NULL DEFAULT 0,                        ##物品id
    `FItemName`            VARCHAR(32) NOT NULL,                                       ##物品名称
    `FCode`                VARCHAR(32) NOT NULL,                                       ##物品代码
    `FSend`                TINYINT  NOT NULL DEFAULT '1',
    `FSendTime`            DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `FTime`                DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',            ##投票时间（默认写入当前时间）
    `FDate`                DATE NOT NULL DEFAULT '0000-00-00',                         ##投票日期
    `FMemo`                VARCHAR(255) DEFAULT NULL,                                  ##备注
    PRIMARY KEY (`FId`),
    KEY `INDEX_FQQ` (`FQQ`),
    KEY `INDEX_DATE_ITEM` (`FDate`,`FItem`),
    KEY `INDEX_FSEND` (`FSend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;