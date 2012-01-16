<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2008 BY  TENCENT  CORPORATION.  ALL RIGHTS
 *     RESERVED.   NO  PART  OF THIS PROGRAM  OR  PUBLICATION  MAY
 *     BE  REPRODUCED,   TRANSMITTED,   TRANSCRIBED,   STORED  IN  A
 *     RETRIEVAL SYSTEM, OR TRANSLATED INTO ANY LANGUAGE OR COMPUTER
 *     LANGUAGE IN ANY FORM OR BY ANY MEANS, ELECTRONIC, MECHANICAL,
 *     MAGNETIC,  OPTICAL,  CHEMICAL, MANUAL, OR OTHERWISE,  WITHOUT
 *     THE PRIOR WRITTEN PERMISSION OF :
 *
 *                        TENCENT  CORPORATION
 *
 *       Advertising Platform R&D Team, Advertising Platform & Products
 *       Tencent Ltd.
 *---------------------------------------------------------------------------
 */
/**
 * 用户活动信息数据对象
 *
 * @package sdk.mvc.src.framework.dao
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMUser.class.php 2010-1-6 by ianzhang
 */
class TMUser extends TMObject{
    const FQQ = "FQQ";
    const FVoteCount = "FVoteCount";
    const FScoreCount = "FScoreCount";

    /**
     * 构造函数
     * @param string $type  DAO别名
     * @param array $attributes  属性容器
     * @param int $mode  DAO加载模式
     * @return void
     */
    public function __construct($type = "user", array $attributes = array(), $mode = self::MODE_NORMAL)
    {
        parent::__construct($type, $attributes, $mode);
    }

    /**
     * 设置user QQ号码
     *
     * @param  string $qq     qq号码
     */
    public function setQQ($qq)
    {
        $this->setOriginal(self::FQQ, $qq);
    }

    /**
     * 获得user QQ号码
     *
     * @return string $qq    qq号码
     */
    public function getQQ()
    {
        return $this->getOriginal(self::FQQ);
    }

    /**
     * 得到该用户的被投票数
     *
     * @return $mixed $voteCount    投票数
     */
    public function getVoteCount()
    {
        return $this->getOriginal(self::FVoteCount);
    }

    /**
     * 得到该用户的积分
     *
     * @return mixed $scoreCount    得到积分数
     */
    public function getScoreCount()
    {
        return $this->getOriginal(self::FScoreCount);
    }

}