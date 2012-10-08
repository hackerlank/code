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
 * TMDAOFactory
 * Data access object factory
 *
 * @package sdk.mvc.src.framework.dao
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMDAOFactory.class.php 2009-12-29 by ianzhang
 */
class TMDAOFactory {
    /**
     * 存放dao实例的数组，每个类型dao一个实例
     *
     * @var array
     */
    protected static $daoArray = array();

    /**
     * 根据配置文件得到一个DAO
     *
     * @param  string $type     需要dao的类型,配置在config/dao.yml
     * @param  boolean $lazyLoad      是否惰性加载，默认是执行惰性加载
     * @return TMDAOInterface $dao    dao接口
     * @throws TMDAOException
     */
    public static function getDAO($type, $lazyLoad = true)
    {
        if(!isset(self::$daoArray[$type]))//查看是否存在该类型的DAO
        {
            $configArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/dao/dao.yml");

            if(!isset($configArray[$type]))//配置文件出错
            {
                throw new TMDAOException("No this DAO:".$configArray[$type]);
            }

            $daoClassName = $configArray[$type]["dao"];
            if($lazyLoad)
            {
               $dao = new $daoClassName($type);
            }
            else{
                $dao = new $daoClassName($type, TMDAO::LOAD_ALL);
            }
            $daoArray[$type] = $dao;
        }
        return $daoArray[$type];
    }

    /**
     * 根据配置得到一个baseUser DAO
     *
     * @param  boolean $lazyLoad      是否惰性加载，默认是执行惰性加载
     * @return TMDAOInterface    DAO接口
     */
    public static function getBaseUserDAO($lazyLoad = true)
    {
        return self::getDAO("baseUser", $lazyLoad);
    }

    /**
     * 根据配置得到一个user DAO
     *
     * @param  boolean $lazyLoad      是否惰性加载，默认是执行惰性加载
     * @return TMDAOInterface    DAO接口
     */
    public static function getUserDAO($lazyLoad = true)
    {
        return self::getDAO("user", $lazyLoad);
    }

}