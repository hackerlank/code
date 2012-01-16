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
 * Mysql data access object
 *
 * @package sdk.mvc.src.framework.dao
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMMysqlDAO.class.php 2010-1-6 by ianzhang
 */
class TMMysqlDAO extends TMDAO implements TMDAOInterface {
    /**
     * @var TMService
     */
    protected $ts;
    /**
     * 数据库表中的表名
     * @var string
     */
    protected $tableName;
    /**
     * 数据库表中的自增ID字段名
     * @var string
     */
    protected $autoKeyName;
    /**
     * 数据库表中的逻辑主键名数组.比如Tbl_User表，物理主键为FUserId,逻辑主键为FQQ，则这里应该是array('FQQ')
     * @var array
     */
    protected $tableKeys;

    /**
     * 构造函数
     *
     * @param  string $type     dao类型别名
     * @param  int $loadType    dao加载数据的模式
     * @throws TMConfigException
     */
    public function __construct($type, $loadType = TMDAO::LOAD_LAZY)
    {
        //调用父类构造函数
        parent::__construct($type, $loadType);
        //设置数据库
        if(!isset($this->configArray[$this->type]["dbAlias"]))
        {
           $this->ts = new TMService();
        }
        else{
            $this->ts = new TMService($this->configArray[$this->type]["dbAlias"]);
        }
        //设置表名
        if(!isset($this->configArray[$this->type]["table"]))
        {
           throw new TMConfigException("Need mysql ".$this->type." table in dao.yml");
        }
        $this->tableName = $this->configArray[$this->type]["table"];
        //设置自增字段
        if(!isset($this->configArray[$this->type]["autoKey"]))
        {
            throw new TMConfigException("Need mysql ".$this->type." autoKey in dao.yml");
        }
        $this->autoKeyName = $this->configArray[$this->type]["autoKey"];
        //设置逻辑主键数组
        if(!is_array($this->configArray[$this->type]["keys"])
            || empty($this->configArray[$this->type]["keys"]))
        {
            throw new TMConfigException("Need mysql ".$this->type." keys in dao.yml");
        }
        $this->tableKeys = $this->configArray[$this->type]["keys"];
    }

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

//        $lockKey = $this->tableName;
//        foreach($keyArray as $key => $value)
//        {
//            $lockKey .= "_".$value;
//        }
//
//        if(TransactionService::getLockStatus($lockKey))
//        {
//            $updateStr = "";
//            $updateWhereStr = "";
//            foreach($keyArray as $key => $value)
//            {
//                $updateStr .= $key." = ".$key.",";
//            }
//            $updateStr = rtrim($updateStr, ",");
//            $sqlStr = "update ".$this->tableName." set ".$updateStr." where ".TMUtil::buildWhereString($keyArray);
//            $this->ts->query($sqlStr);
//            TransactionService::setLockStatus($lockKey, true);
//        }

        if($mode != TMObject::MODE_READONLY && TransactionService::getTransactionStatus())
        {
            $result = $this->ts->selectForUpdate($keyArray,"*",$this->tableName,array(0,1),null, MYSQLI_ASSOC);
        }
        else
        {
            $result = $this->ts->select($keyArray,"*",$this->tableName,array(0,1),null, MYSQLI_ASSOC);
        }

        if(empty($result))
        {
            return null;
        }

        $object = $this->createObject($result[0],$mode);

        return $object;
    }

    /**
     * 将数据对象保存回去
     *
     * @param  TMObject $object     数据对象
     */
    public function save($object)
    {
        try{
            $attributes = $object->getAllOriginal();
            $array = $object->getSetArray();

            //该对象是否在数据库中有记录
            $isExsited = false;
            //逻辑主键构成查询数组
            $searchArray = array();
            //是否需要去数据库中查找查找对象是否存在
            $needSelect = true;


            //构造逻辑主键构成的查询数组
            foreach($this->tableKeys as $value)
            {
                if(isset($attributes[$value]))
                {
                    $searchArray[$value] = $attributes[$value];
                }else{//如果对象中不存在该键值，则数据库中不存在该对象，无需查找对象是否存在，直接插入数据库
                    $searchArray[$value] = "";
                    $needSelect = false;
                    break;
                }
            }

            if(isset($attributes[$this->autoKeyName]))
            {
                $autoKey = $attributes[$this->autoKeyName];
                $isExsited = true;
                $needSelect = false;
            }

            if($needSelect)//查找数据库中该对象数据
            {
                $autoKeyResult = $this->ts->select($searchArray,$this->autoKeyName,$this->tableName,array(0,1));

                if(!empty($autoKeyResult))//对象存在数据库中
                {
                    $isExsited = true;
                    $autoKey = $autoKeyResult[0][$this->autoKeyName];
                }
            }

            if(!$isExsited)//对象不在数据库中，插入
            {
                $this->ts->insert($array,$this->tableName);
                $autoKey = $this->ts->getInsertId();
            }
            else{//已存在，更新
                //更新对象时的sql中的where子句
                $updateString = TMUtil::buildWhereString($searchArray, TMConfig::get("mysqlStringColumns"));
                $this->ts->update($array, $updateString, $this->tableName);
            }
            //设置对象的自制字段的值
            $object->set($this->autoKeyName, $autoKey);
        }
        catch(TMMysqlException $me)
        {
            throw new TMDAOException("Save ".$this->type." object failed by mysql: ".$me->getMessage());
        }
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

        $array = $this->ts->select($conditions, "*", $this->tableName, $limitArray, $otherArray, MYSQLI_ASSOC);
        $listArray = array();
        foreach($array as $attributeArray)
        {
            $listArray[] = $this->createObject($attributeArray, $mode);
        }
        return $listArray;
    }

    /**
     * 得出符合条件数据的个数
     *
     * @param  array $conditions     得到条件数组
     * @return int $count
     */
    public function count(array $conditions)
    {
        $count = $this->ts->getCount($conditions, $this->tableName, TransactionService::getTransactionStatus());
        return $count;
    }

}