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
 * Data access object interface
 *
 * @package sdk.mvc.src.framework.dao
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMDAOInterface.class.php 2010-1-6 by ianzhang    
 */
interface TMDAOInterface {
    /**
     * 根据Key来得到需要的对象
     *
     * @param  array $keyArray    array("FQQ" => $qq, "CampaignId" => $id)
     * @param  int $mode          创建数据对象的模式，默认是正常读写对象
     * @param  array $fieldArray  array("FFAddr") 
     * @return TMObject      数据对象
     */
    public function findByKey(array $keyArray, $mode = TMObject::MODE_NORMAL, array $fieldArray = array());
    /**
     * 将对象保存回DB或者TTC
     *
     * @param  TMObject $object     数据对象 
     */
    public function save($object);
    /**
     * 根据条件查询得到数据对象
     *
     * @param  array $conditions    where condition
     *                              example: array("FQQ" => '10000', "FUserId" => 1),array("eq" => array("FQQ" => '10000'))
     * @param  array $limitArray    the limit array, for example: array(0,10)
     * @param  array $otherArray    the other array, for example: array("orderby")
     * @return array                the add's result rows 
     */
    public function findObjects(array $conditions, $limitArray = null, $otherArray = null, $mode = TMObject::MODE_NORMAL);
    /**
     * 得出符合条件数据的个数
     *
     * @param  array $conditions     得到条件数组 
     * @return int $count 
     */
    public function count(array $conditions);
}