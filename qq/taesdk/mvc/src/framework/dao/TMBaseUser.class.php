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
 * 用户基本信息数据对象
 *
 * @package sdk.mvc.src.framework.dao
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMBaseUser.class.php 2010-1-6 by ianzhang    
 */
class TMBaseUser extends TMObject{
    /**
     * 构造函数
     * @param string $type  DAO别名
     * @param array $attributes  属性容器
     * @param int $mode  DAO的运行模式
     * @return void
     */
    public function __construct($type = "baseUser", array $attributes = array(), $mode = self::MODE_NORMAL)
    {
        parent::__construct($type, $attributes, $mode);
    }
}