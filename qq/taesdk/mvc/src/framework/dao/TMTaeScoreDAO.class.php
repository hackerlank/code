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
 * Tae score data access object
 *
 * @package sdk.mvc.src.framework.dao
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMTaeScoreDAO.class.php 2010-1-6 by ianzhang
 */
class TMTaeScoreDAO extends TMTaeDAO implements TMDAOInterface {

    /**
     * 根据Key来得到需要的对象
     *
     * @param  array $keyArray    array("FQQ" => $qq, "CampaignId" => $id)
     * @param  int $mode          创建数据对象的模式，默认是正常读写对象
     * @param  array $fieldArray  array("FFAddr")
     * @return TMObject      数据对象
     */
    public function findByKey(array $keyArray, $mode = TMObject::MODE_NORMAL, array $fieldArray = array())
    {
        if(empty($keyArray))
        {
            return null;
        }

        if(!isset($keyArray["uin"]))
        {
            return null;
        }
        
        if(!isset($keyArray["typelist"]))
        {
            $keyArray["typelist"] = array(0);
        }
        
        if(!is_array($keyArray["typelist"]))
        {
            $keyArray["typelist"] = array(trim($keyArray["typelist"]));
        }
        
        //执行TAE的命令
        $resultArray = TaeScoreService::queryScore($keyArray["uin"],$keyArray["typelist"]);
        
        if($resultArray["retcode"] != 0)
        {
            throw new TMDAOException("Find by key ".$this->type
            ." object failed by tae. retcode is ".$resultArray["retcode"]
            ." msg is ".$resultArray["rspmsg"]);
        }
        
        $fieldList = $resultArray["fieldlist"];
        if(!isset($fieldList["FModifyTime"]))
        {
            $fieldList = $fieldList[0];
        }

        $object = $this->createObject($fieldList, $mode);

        return $object;
    }

    /**
     * 将数据对象保存回去
     *
     * @param  TMObject $object     数据对象
     */
    public function save($object)
    {
        //获取对象属性
        $array = $object->getAll();
        
        $toUin = isset($array["uin"]) ? $array["uin"] : $array["toUin"];
        $scoreVal = isset($array["scoreVal"]) ? $array["scoreVal"] : 0;
        $scoreType = isset($array["scoreType"]) ? $array["scoreType"] : 1;
        $strictType = isset($array["strictType"]) ? $array["strictType"]: 0;
        $strictVal = isset($array["strictVal"]) ? $array["strictVal"] : 0;
        $fromUin = isset($array["fromUin"]) ? $array["fromUin"]: 0; 
        $sourceId = isset($array["sourceId"]) ? $array["sourceId"]: 11;
        $remark = isset($array["remark"])? $array["remark"] : "";
        
        $resultArray = TaeScoreService::updateScore($toUin, $scoreVal, $scoreType, $strictType
            , $strictVal, $fromUin, $sourceId, $remark);
        
        if($resultArray["retcode"] != 0)
        {
            throw new TMDAOException("save ".$this->type
            ." object failed by tae. retcode is ".$resultArray["retcode"]
            ." msg is ".$resultArray["rspmsg"], $resultArray["retcode"]);
        }
        
        return $resultArray["rows"];
    }
    
    /**
     * 根据条件查询得到数据对象
     *
     * @param  array $conditions    where condition
     *                              example: array("FQQ" => '10000', "FUserId" => 1),array("eq" => array("FQQ" => '10000'))
     * @param  array $limitArray    the limit array, for example: array(0,10)
     * @param  array $otherArray    the other array, for example: array("orderby")
     * @param  int $mode            生成对象的模式
     * @return array                the add's result rows
     */
    public function findObjects(array $conditions, $limitArray = null, $otherArray = null, $mode = TMObject::MODE_NORMAL)
    {
        throw new TMDAOException("TAE Dao can't find Objects");
    }

    /**
     * 得出符合条件数据的个数
     *
     * @param  array $conditions     得到条件数组
     * @return int $count
     */
    public function count(array $conditions)
    {
        throw new TMDAOException("TAE Dao can't count");
    }
}