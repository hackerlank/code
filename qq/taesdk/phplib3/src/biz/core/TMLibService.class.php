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
 * 数据库操作类
 *
 * @package sdk.lib3.src.biz.core
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMService.class.php 2008-9-6 by ianzhang
 */
class TMLibService
{
    /**
     * @var TMMysqlAdapter
     *
     * @access protected
     */
    protected $db;

    /**
     * @var array
     *
     * @access protected
     */
    protected static $dbArray = array();
    protected $arrayStringColumn = array();
    protected $dbAlias;
    /**
     * 构造函数
     *   不自动连接数据库
     */
    public function __construct($dbAlias)
    {
        $this->dbAlias = $dbAlias;
        //$this->db = $this->getConn ($dbAlias, $autoCommit, $arrayStringColumn);
    }

    /**
     * get db adapter instance
     *
     * @access public
     * @return TMMysqlAdapter $db db adapter instance
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * 设置dbAlias
     * @param $dbAlias
     */
    public function setDbAlias($dbAlias)
    {
        $this->dbAlias = $dbAlias;
    }

    /**
     * 获得dbAlias
     * @return string
     */
    public function getDbAlias()
    {
        return $this->dbAlias;
    }

    /**
     * 关闭连接
     */
    public function closeConnection()
    {
        $this->db->closeConnection();
        unset(self::$dbArray[$this->dbAlias]);
    }

    /**
     * 关闭所有数据库连接
     */
    public static function closeAllConnection()
    {
        foreach(self::$dbArray as $key => $db)
        {
            $db->closeConnection();
            unset(self::$dbArray[$key]);
        }
    }

    /**
     * 打开连接
     * @param array $dbConfig 数据库配置,host,username,password,dbname
     * @param $autoCommit
     * @param $arrayStringColumn
     * @param boolean $forcely
     */
    public function openConnection($dbConfig, $autoCommit = true, $arrayStringColumn = array(), $forcely = false)
    {       
        if(!isset(self::$dbArray[$this->dbAlias]) || $forcely == true)
        {
            $host = $dbConfig["host"];
            $username = $dbConfig["username"];
            $password = $dbConfig["password"];
            $dbname = $dbConfig["dbname"];
            $port = isset($dbConfig["port"]) ? $dbConfig["port"] : 3306;
            
            if(empty($this->db))
            {
                $this->db = $this->getConn($dbConfig, $autoCommit, $arrayStringColumn);
            }
            else{
                $this->db->openConnection($host, $username, $password, $dbname, $port, $autoCommit);
            }
            self::$dbArray[$this->dbAlias] = $this->db;
        }
    }
    
    /**
     * Get the connection database
     *
     * @param  array          $dbConfig  数据库配置,host,username,password,dbname,port
     * @param  boolean        $autoCommit is auto commit
     * @param  array          $arrayStringColumn 字符类型的字段
     * @return TMMysqlAdapter $database    The connection database
     */
    protected function getConn($dbConfig, $autoCommit = true, $arrayStringColumn = array())
    {
        $dbAlias = $this->dbAlias;
        $this->arrayStringColumn = $arrayStringColumn;

        $dbAlias = empty($dbAlias) ? 'default' : (string)$dbAlias;
        $db = isset(self::$dbArray[$dbAlias]) ? self::$dbArray[$dbAlias] : null;
        if($db == null)
        {
            $host = $dbConfig["host"];
            $username = $dbConfig["username"];
            $password = $dbConfig["password"];
            $dbname = $dbConfig["dbname"];
            $port = isset($dbConfig["port"]) ? $dbConfig["port"] : 3306;

            $db = new TMMysqlAdapter($host, $username, $password, $dbname, $port, $autoCommit);
            $db->setArrayStringColumn($this->arrayStringColumn);
            self::$dbArray[$dbAlias] = $db;
        }

        return $db;
    }

    /**
     * The customer sql query
     *
     * @param string $sql     the sql string
     * @param int $resultType  for example: MYSQLI_BOTH
     * @return TMMysqlResult $object
     * @throws TMMysqlException
     */
    public function query($sql, $resultType = MYSQLI_BOTH)
    {
        $result = $this->db->query ( $sql, $resultType);
        if (! $result->isEmpty ())
        {
            $rows = $result->getAllRows ();
            return $rows;
        }
        else
        {
            return array();
        }
    }
    
    private function sqlReplace($key){
            $key = str_replace('\\','\\\\',$key);
            $key = str_replace("'","\'",$key);
            return $key;
    }

    /**
     * The customer sql query
     *
     * @param string $sql     the sql string
     * @param int $resultType  for example: MYSQLI_BOTH
     * @return TMMysqlResult $object
     * @throws TMMysqlException
     */
    public function bindQuery($sql, $bindParam=array())
    {
        TMDebugUtils::debugLog($sql);
        if(!empty($bindParam)){
            foreach($bindParam as $key=>$val){
                if($key[0] != '$') continue;
                $val = $this->sqlReplace($val);
                $sql = str_replace($key,$val,$sql);
            }
        }
        TMDebugUtils::debugLog($sql);
        
        $result = $this->db->query ( $sql);
        if (! $result->isEmpty ())
        {
            $rows = $result->getAllRows ();
            return $rows;
        }
        else
        {
            return array();
        }
    }
    

    /**
     * Set time in update or insert array
     *
     * @param array $field     the insert or update array
     * @param string $column    the presented time column name
     */
    public static function setTimeForUpdateOrInsert(array &$field, $column = 'FTime')
    {
        $field [$column] = date ( 'Y-m-d H:i:s' );
    }

    /**
     * Set date in update or insert array
     *
     * @param array $field     the insert or update array
     * @param string $column    the presented date column name
     */
    public static function setDateForUpdateOrInsert(array &$field, $column = 'FDate')
    {
        $field[$column] = date ('Y-m-d');
    }

    /**
     * Get data count by where sql
     *
     * @param array $conditions  where condition, example: array("FQQ" => '10000', "FUserId" => 1),array("eq" => array("FQQ" => '10000'))
     * @param string $table      the table name
     * @param boolean $forUpdate
     *
     * @throws TMMysqlException
     */
    public function getCount(array $conditions, $table, $forUpdate=false)
    {
        $sqlstr = $this->db->makeSQLString($conditions, 'count(*) c', $table);
        if ($forUpdate)
        {
            $sqlstr .= " for update";
        }
        $result = $this->db->query($sqlstr);
        $rows = $result->getAllRows();

        return (int) $rows[0]['c'];
    }

    /**
     * 只获取一行数据, 参数同select
     * @see select
     */
    public function selectOne(array $conditions, $select, $table='', $limitArray= null, $otherArray = null, $resultType = MYSQLI_BOTH) {
        $rs = $this->select($conditions, $select, $table, array(0,1), $otherArray , $resultType);
        if(count($rs) > 0) {
            return array_pop($rs);
        }
        else {
            return null;
        }
    }

    /**
     * Select data by where sql
     *
     * @param  array $conditions           where condition
     *                                     example: array("FQQ" => '10000', "FUserId" => 1),array("eq" => array("FQQ" => '10000'))
     * @param  string $select              the select columns of the sql
     * @param  string $table               the table name
     * @param  array $limitArray           the limit array, for example: array(0,10)
     * @param  array $otherArray           the other array, for example: array("orderby")
     * @param  int $resultType             SQL返回数据的类型，包括MYSQLI_BOTH,MYSQLI_ASSOC,MYSQLI_NUM
     *
     * @return result                      the add's result rows or false
     * @throws TMMysqlException
     */
    public function select(array $conditions, $select, $table='', $limitArray= null, $otherArray = null, $resultType = MYSQLI_BOTH)
    {
        $sqlstr = $this->db->makeSQLString($conditions,$select,$table,$limitArray,$otherArray);
        $result = $this->db->query ( $sqlstr,$resultType);
        if (! $result->isEmpty())
        {
            $rows = $result->getAllRows();
            return $rows;
        }
        else
        {
            return array();
        }
    }

    /**
     * Select data by where sql for update
     *
     * @param  array $conditions           where condition
     *                                     example: array("FQQ" => '10000', "FUserId" => 1),array("eq" => array("FQQ" => '10000'))
     * @param  string $select              the select columns of the sql
     * @param  string $table               the table name
     * @param  array $limitArray           the limit array, for example: array(0,10)
     * @param  array $otherArray           the other array, for example: array("orderby")
     * @param  int $resultType             SQL返回数据的类型，包括MYSQLI_BOTH,MYSQLI_ASSOC,MYSQLI_NUM
     * @return result                      the add's result rows or false
     * @throws TMMysqlException
     */
    public function selectForUpdate(array $conditions, $select, $table='', $limitArray= null, $otherArray = null, $resultType = MYSQLI_BOTH)
    {
        $sqlstr = $this->db->makeSQLString($conditions,$select,$table,$limitArray,$otherArray)." for update";
        $result = $this->db->query ( $sqlstr ,$resultType);
        if (! $result->isEmpty())
        {
            $rows = $result->getAllRows();
            return $rows;
        }
        else
        {
            return array();
        }
    }

    /**
     * Add a new data
     *
     * @param  array $insertArray     the insert parameter array
     * @param  string $table          the table name
     * @param  boolean $delay        if True, Add DELAYED in SQL. esp. True when the data DO NOT need to wait the returning, e.g. adding score
     * @return void
     * @throws TMMysqlException
     */
    public function insert(array $insertArray, $table, $delay=false)
    {
        $this->db->doInsert ( $insertArray, $table, $delay );
    }

    /**
     * 获取刚插入的数据id
     * @return int
     */
    public function getInsertId()
    {
        return $this->db->getInsertId ();
    }

    /**
     * update or delete 操作影响的数据行数
     * @return int
     */
    public function getAffectedRowNum()
    {
        return $this->db->getAffectedRowNum ();
    }

    /**
     * Update Data's information
     *
     * @param  array $fields           the update set array
     * @param  mixed $where            the update where string or array
     * @param  string $table           the table name
     *
     * @throws TMMysqlException
     */
    public function update(array $fields, $where, $table)
    {
        $this->db->doUpdate ( $table, $fields, $where );
    }

    /**
     * Commit the DB modification, but you need to set $autoCommit as False.
     */
    public function commit()
    {
        $this->db->commit ();
    }

    /**
     * Rollback the DB modification, but you need to set $autoCommit as False.
     */
    public function rollback()
    {
        $this->db->rollback ();
    }

    /**
     * 开始事务
     *
     */
    public function startTransaction()
    {
        $this->db->startTransaction();
    }

    /**
     * Operate the state
     *
     * @param array $arrColOp     the column numeric operation,
     *                              example: array("FScore" => "+1"),
     *                                       array("FVoteCount" => "*5")
     * @param string $table         the table name
     * @param string $where          the where string
     * @param array $arrColSet    the column update set,
     *                              example: array("FQQ" => '10001', "FCity" => "shanghai")
     *
     * @return boolean true         the operate's result
     * @throws TMMysqlException
     */
    public function operateState($arrColOp, $table, $where=null, $arrColSet=array())
    {
        return $this->db->operate ( $table, $arrColOp, $where, $arrColSet );
    }

    /**
     * Get the data count in one day
     *
     * @param  array $fields     the parameter fields array
     * @param  string $table     the table name
     * @param  string $column    the time column name
     * @param  string $length    the day string length
     *
     * @return int                              the one day count
     * @throws TMMysqlException
     */
    public function getCountOneDay(array $fields, $table, $column = "FTime", $length = "10")
    {
        $today = date ( "Y-m-d" );
        $sqlstr = "select count(*) c from " . $table . " where LEFT(" . $column . "," . $length . ") ='" . $today . "'";
        foreach ( $fields as $key => $field )
        {
            if (is_numeric ( $field ) && intval ( $field ) == $field && ! in_array ( $key, $this->arrayStringColumn ))
            {
                $sqlstr .= " and " . $key . " = " . $field;
            }
            else
            {
                $sqlstr .= " and " . $key . " = '" . $this->db->formatString ( $field ) . "'";
            }
        }
        $result = $this->db->query ( $sqlstr );
        $rows = $result->getAllRows ();
        if (! $rows)
        {
            return false;
        }
        else
        {
            return $rows [0] ['c'];
        }
    }

    /**
     * 设置arrayStringColumn
     * @param array $arrayStringColumn
     * @return void
     */
    public function setArrayStringColumn($arrayStringColumn) {
        $this->arrayStringColumn = $arrayStringColumn;
    }

    public function errno() {
        return $this->db->errno();
    }

    public function error() {
        return $this->db->error();
    }
}