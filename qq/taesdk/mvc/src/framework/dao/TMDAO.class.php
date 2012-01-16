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
 * TMDAO
 * Data access object parent class
 *
 * @package sdk.mvc.src.framework.dao
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMDAO.class.php 2010-1-6 by ianzhang
 */
abstract class TMDAO {
    const LOAD_LAZY = 1;
    const LOAD_ALL = 2;

    /**
     * object的类型
     *
     * @var string
     */
    protected $type;
    /**
     * 生成的对象的类名，有type决定
     *
     * @var string
     */
    protected $className;
    /**
     * 配置文件生成的配置数组
     *
     * @var array
     */
    protected $configArray;
    /**
     * DAO Table配置文件生成的配置数组
     *
     * @var array
     */
    protected $configTableArray;

    /**
     * 主动加载数据库字段数组
     *
     * @var array
     */
    private $hotArray;

    /**
     * 惰性加载数据库字段数组
     *
     * @var array
     */
    private $lazyArray;

    /**
     * 用于检查一个字段是否是数据库字段，是否是热点数据字段
     *
     * @var array
     */
    private $hotMap;

    /**
     * 用于检查一个字段是否是数据库字段，是否是惰性加载数据字段
     *
     * @var array
     */
    private $lazyMap;

    private $loadType = self::LOAD_LAZY;

    /**
     * 构造函数
     *
     * @param  string $type     dao类型别名
     * @param  int $loadType    dao加载数据的模式
     */
    protected function __construct($type, $loadType = TMDAO::LOAD_LAZY)
    {
        $this->loadType = $loadType;
        //设置类型
        $this->setType($type);
        //读取配置
        $this->configArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/dao/dao.yml");
        //设置生成对象的类名,默认TMobject
        if(!isset($this->configArray[$this->type]["object"]))
        {
            $this->className = "TMObject";
        }
        else{
            $this->className = $this->configArray[$this->type]["object"];
        }

        $this->configTableArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/dao/daotable.yml");
        $this->hotMap = array();
        $this->hotArray = array();
        if(is_array($this->configTableArray[$this->type]) && is_array($this->configTableArray[$this->type]["hot"]))
        {
            $hotArrays = $this->configTableArray[$this->type]["hot"];
            foreach($hotArrays as $key => $hotArray)
            {
                foreach($hotArray as $value)
                {
                    $this->hotArray[$key][$value] = "";
                    $this->hotMap[$value] = $key;
                }
            }
        }

        if(is_array($this->configTableArray[$this->type]) && is_array($this->configTableArray[$this->type]["lazy"]))
        {
            $this->lazyMap = array();
            $this->lazyArray = array();
            $lazyArrays = $this->configTableArray[$this->type]["lazy"];
            foreach($lazyArrays as $key => $lazyArray)
            {
                foreach($lazyArray as $value)
                {
                    $this->lazyArray[$key][$value] = "";
                    $this->lazyMap[$value] = $key;
                }
            }
        }
    }

    /**
     * 得到dao的类型
     *
     * @return string    dao的类型
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 设置dao的类型
     *
     * @param  string $type     设置的类型
     */
    protected function setType($type)
    {
        $this->type = $type;
    }

    /**
     * 创建object
     *
     * @param  array $attributes     数据数组
     * @param  int $mode             对象模式
     * @return TMObject             返回TMObject或子类
     */
    protected function createObject(array $attributes, $mode = TMObject::MODE_NORMAL)
    {
        $object = new $this->className($this->type, $attributes, $mode);

        return $object;
    }

    /**
     * 返回主动加载数据库字段数组
     *
     * @return array
     */
    public function getHotArray()
    {
        return $this->hotArray;
    }

    /**
     * 返回惰性加载数据库字段数组
     *
     * @return array
     */
    public function getLazyArray()
    {
        return $this->lazyArray;
    }

    /**
     * 返回hotMap
     *
     * @return array
     */
    public function getHotMap()
    {
        return $this->hotMap;
    }

    /**
     * 返回lazyMap
     *
     * @return array
     */
    public function getLazyMap()
    {
        return $this->lazyMap;
    }

    /**
     * 返回加载模式
     *
     * @return int $loadType    加载模式
     */
    public function getLoadType()
    {
        return $this->loadType;
    }
}