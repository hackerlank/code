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
 * TMObject
 * the data info parent class
 *
 * @package sdk.mvc.src.framework.dao
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMObject.class.php 2009-12-30 by ianzhang
 */
class TMObject {
    const MODE_NORMAL = 1;
    const MODE_READONLY = 2;

    /**
     * 对象属性数组
     * @var array
     */
    private $attributes = array();
    /**
     * 对象模式
     * @var array
     */
    private $mode = self::MODE_NORMAL;
    /**
     * 数据类型
     * @var string
     */
    protected $dataType;
    /**
     *  属性映射表
     * @var array
     */
    protected $map = array();

    protected $configTableArray = array();
    protected $configDAOArray = array();

    protected $setArray = array();

    /**
     * 构造函数
     *
     * @param  string $type     数据类型
     * @param  array $attributes   对象属性
     * @param  int $mode           对象模式
     * @throws TMConfigException
     */
    public function __construct($type, array $attributes = array(), $mode = self::MODE_NORMAL)
    {
        $this->dataType = $type;
        //载入配置数组
        $configArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/dao/daomap.yml");
        if(isset($configArray[$this->dataType]))
        {
            if(is_array($configArray[$this->dataType]))
            {
                //设置映射表
                $this->map = $configArray[$this->dataType];
            }
            else if(!empty($configArray[$this->dataType])){
                //配置文件出错
                throw new TMConfigException("Config error in daomap.yml: ".$this->dataType." should be array");
            }
        }

        $this->configTableArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/dao/daotable.yml");
        $this->configDAOArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/dao/dao.yml");

        foreach($attributes as $name => $value)
        {
            $fieldName = $name;
            if(isset($this->map[$name]))
            {
                $fieldName = $this->map[$name];
            }
            $this->setOriginal($fieldName, $value);
        }
        $this->setMode($mode);
    }

    /**
     * 获取对象属性，使用了映射表
     * @param string $name 属性名
     * @return  mixed          属性值
     */
    public function get($name)
    {
        if(isset($this->map[$name]))
        {
            $name = $this->map[$name];
        }

        return $this->getOriginal($name);
    }

    /**
     * 设置对象属性，使用了映射表
     * @param string $name 属性名
     * @param mixed $value 属性值
     */
    public function set($name, $value)
    {
        $this->checkWritable();
        $fieldName = $name;
        if(isset($this->map[$name]))
        {
            $fieldName = $this->map[$name];
        }
        $this->setArray[$fieldName] = $value;
        $this->setOriginal($fieldName, $value);
    }

    /**
     * 返回有修改的属性和属性对应的值
     * @return array
     */
    public function getSetArray()
    {
        return $this->setArray;
    }

    /**
     * 批量设置对象属性，使用了映射表
     * @param array $array 属性数组
     */
    public function setAll(array $array)
    {
        $this->checkWritable();
        foreach($array as $name => $value)
        {
            $this->set($name, $value);
        }
    }

    /**
     * 获取所有对象属性，使用了映射表
     * @return array 属性数组
     */
    public function getAll()
    {
        $resultArray = $this->attributes;

        foreach($this->map as $key => $value)
        {
            $resultArray[$key] = $resultArray[$value];
            unset($resultArray[$value]);
        }

        return $resultArray;
    }

    /**
     * 获取对象属性，不使用映射表
     * @param string $name 属性名
     * @return  mixed          属性值
     */
    public function getOriginal($name)
    {
        $value = $this->attributes[$name];

        if($value === null)
        {
            $keyNamesArray = $this->configDAOArray[$this->dataType]["keys"];
            $keyArray = array();
            foreach($keyNamesArray as $keyName)
            {
                if($this->attributes[$keyName] === null)
                {
                    throw new TMDAOException("No set primary keys for getting dao");
                }
                $keyArray[$keyName] = $this->attributes[$keyName];
            }

            $object = TMDAOFactory::getDAO($this->dataType)->findByKey($keyArray, $this->mode, array($name));
            if($object != null)
            {
                $this->setAllOriginal($object->getAllOriginal());
                $value = $this->attributes[$name];
            }
        }

        return $value;
    }

    /**
     * 设置对象属性，使用了映射表
     * @param string $name 属性名
     * @param mixed $value 属性值
     */
    public function setOriginal($name, $value)
    {
        $this->checkWritable();
        $this->attributes[$name] = $value;
    }

    /**
     * 批量设置对象属性，不使用映射表
     * @param array $array 属性数组
     */
    public function setAllOriginal(array $array)
    {
        $this->checkWritable();
        $this->attributes = array_merge($array,$this->attributes);
    }

    /**
     * 获取所有对象属性，不使用映射表
     * @return array 属性数组
     */
    public function getAllOriginal()
    {
        return $this->attributes;
    }

    /**
     * 保存该对象到数据库中
     *
     */
    public function save()
    {
        $this->checkWritable();
        TMDAOFactory::getDAO($this->dataType)->save($this);
    }

    /**
     * 得到当前对象的模式
     *
     * @return int $mode    模式类型
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * 设置对象模式
     *
     * @param  int $mode     模式类型
     */
    protected function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * 判断是否是可读，如果是可读抛出异常
     *
     * @throws TMDataObjectException
     */
    protected function checkWritable()
    {
        if($this->mode == self::MODE_READONLY)
        {
            throw new TMDataObjectException("The object can't write data");
        }
    }

}