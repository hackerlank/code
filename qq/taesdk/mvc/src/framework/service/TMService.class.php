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
 * TMService
 * 访问数据库的service
 *
 * @package sdk.mvc.src.framework.service
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMService.class.php 2010-12-27 by ianzhang    
 */
class TMService extends TMLibService{
    protected $dbConfig;
    
    /**
     * Get the connection database
     *
     * @param string  $dbConfig  数据库配置
     * @param boolean $autoCommit is auto commit
     * @return void
     */
    public function __construct($dbAlias = 'default', $autoCommit = true) {
        parent::__construct($dbAlias);
        $this->db = $this->getConn($autoCommit);
    }
    
    /**
     * 打开连接
     * @param boolean $autoCommit 是否自动提交，默认是自动提交
     */
    public function openConnection($autoCommit = true)
    {
        $dbConfig = $this->dbConfig;
        $arrayStringColumn = $this->arrayStringColumn;
        
        parent::openConnection($dbConfig, $autoCommit, $arrayStringColumn);
    }
    
    /**
     * 重新打开连接
     * @param boolean $autoCommit 是否自动提交，默认是自动提交
     */
    public function reconnect($autoCommit = true)
    {
        $dbConfig = $this->dbConfig;
        $arrayStringColumn = $this->arrayStringColumn;
        
        parent::openConnection($dbConfig, $autoCommit, $arrayStringColumn, true);
    }
    
    /**
     * 得到一个数据库连接
     * @see TMLibService#getConn($dbConfig, $autoCommit, $arrayStringColumn)
     */
    protected function getConn($autoCommit = true) {
        $dbAlias = $this->dbAlias;
        $serverType = $_ENV['SERVER_TYPE'];
        if(!isset($serverType))
        {
            $serverType = 'production';
        }

        if(TMConfig::get("db", $dbAlias) != null)
        {
            $configArray = array();
            $configArray[$dbAlias] = array();
            $configArray[$dbAlias][$serverType] = TMConfig::get("db", $dbAlias);
            $configArray[$dbAlias][$serverType]["dbname"] = TMConfig::get("dbname");
        }
        
        $configDBArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/db.yml");
        if(TMConfig::get("db", $dbAlias) == null && !isset($configDBArray[$dbAlias])){
            $dbAlias = "default";
            $configArray = array();
            $configArray["default"] = array();
            $configArray["default"][$serverType] = TMConfig::get("db", "default");
            if($configArray["default"][$serverType] == null)
            {
                throw new TMConfigException("Load db config file error in TMService.class.php: dbAlias doesn't exsit");
            }
            $configArray["default"][$serverType]["dbname"] = TMConfig::get("dbname");
        }
        
        if(isset($configDBArray[$dbAlias]))
        {
            if(empty($configArray) && !isset($configDBArray[$dbAlias][$serverType]))
            {
                throw new TMConfigException("Load db config file error in TMService.class.php: serverType error");
            }
            
            if(isset($configDBArray[$dbAlias][$serverType]))
            {
                foreach($configDBArray[$dbAlias][$serverType] as $key => $value)
                {
                    $configArray[$dbAlias][$serverType][$key] = $value;
                }
            }
            
            $dbName = $configDBArray[$dbAlias]["dbname"];
            if(isset($dbName)){
                $configArray[$dbAlias][$serverType]["dbname"] = $dbName;
            }else{
                $configArray[$dbAlias][$serverType]["dbname"] = TMConfig::get("dbname");
            }
        }

        $dbConfig = $configArray[$dbAlias][$serverType];
        
        $this->dbConfig = $dbConfig;
        $arrayStringColumn = TMConfig::get("mysqlStringColumns");

        return parent::getConn($dbConfig, $autoCommit, $arrayStringColumn);
    }
}
?>
