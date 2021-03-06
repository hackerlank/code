<?php
	/**
	@author:shenjian
	@email:xiaoshengeer@gmail.com
	@create:2012-05-08 10:05:33
	@encoding:utf8 sw=4 ts=4
	**/
class DBSingleMysqli
{
    private $dbInstance;
    public $errLogFile;
    public $compare = array('=', '!=', '>', '>=', '<', '<=', 'IN', 'LIKE', 'NULL', 'NOT NULL');
    
    public function __construct($dbHost, $dbUser, $dbUserPwd, $dbName, $errLogFile='/tmp/mysql.err.log', $dbCharset="utf8", $port=3306)
    {
        $this->errLogFile = $errLogFile;
        $this->dbInstance = new mysqli($dbHost, $dbUser, $dbUserPwd, $dbName);
        if ($this->dbInstance->connect_error) {
            $this->errLog($this->dbInstance->errno, $this->dbInstance->error);
            die($this->dbInstance->error);
        }
        
        $this->dbInstance->set_charset($dbCharset);
    }
    
    public function __destruct()
    {
        if (is_resource($this->dbInstance))
            $this->dbInstance->close();
    }

    public function getRows($table, $fileds='*', $option = $array('where'=>array(),'order'=>'','limit'=>''))
    {
        $sql = " SELECT $fileds FROM $table ";

        if ($option['where']) $sql .= $this->parseWhere($option['where']);
        if ($option['order']) $sql .= " ORDER BY {$option['order']} ";
        if ($option['limit']) $sql .= " LIMIT {$option['limit']} ";
        
        $res = $this->query($sql);
        if ($res) {
            $resList = array();
            while ($row = $res->fetch_assoc())
                $resList[] = $row;

            $res->free();
            return $resList;
        } else
            return false;
    }
    
    public function getRow($table, $fileds='*',$option = array('where'=>array(), 'order'=>''))
    {
        $sql = " SELECT $fileds FROM $table ";

        if ($option['where']) $sql .= $this->parseWhere($option['where']);
        if ($option['order']) $sql .= " ORDER BY {$option['order']} ";
        $sql .= " LIMIT 1";
        
        $res = $this->query($sql);
        if ($res) {
            $resRow = array();
            $resRow = $res->fetch_array();
        
            $res->free();
            return $resRow;
        } else
            return false;
    }
    
    public function getOne($table, $filed, $where='')
    {
        $sql = "SELECT $filed FROM $table";

        if ($where) $sql .= $this->parseWhere($where);
        
        $res = $this->query($sql);
        if ($res) {
            $resFileds = $res->fetch_array();
        
            $res->free();
            return $resFileds[0];
        } else 
            return false;
    }
    
    public function insert($table, $dataArray)
    {
        foreach ($dataArray as $k=>$v) {
            $filedstr .= "$k,";
            if (is_string($v))
                $filedval .= "'".$this->formatString($v)."',";
            else
                $filedval .= "$v,";
        }
       
        $filedstr = preg_replace('/,$/', '', $filedstr);
        $filedval = preg_replace('/,$/', '', $filedval);
        
        $sql = "INSERT INTO $table ($filedstr) VALUES ($filedval)";
        return $this->query($sql);
    }

    public function getInsertId()
    {
        return $this->dbInstance->insert_id;
    }
    
    public function update($table, $dataArray, $where)
    {
        foreach ($dataArray as $k=>$v) {
            $filedval .= "$k=";
            if (is_string($v))
                $filedval .= "'$v',";
            else
                $filedval .= "$v,";
        }
        $filedval = preg_replace('/,$/', '', $filedval);
        
        $sql = "UPDATE $table SET $filedval ".$this->parseWhere($where);
        if ($this->query($sql))
            return true;
        else
            return false;
    }
    
    public function delete($table,$where)
    {
        $sql = "DELETE FROM $table ".$this->parseWhere($where);
        if ($this->query($sql))
            return true;
        else 
            return false;
    }
    public function startTransaction()
    {
        $this->dbInstance->autocommit(FALSE);
    }
    public function commit()
    {
        $res = $this->dbInstance->commit();
        if (!$res) $this->errLog($this->dbInstance->errno, $this->dbInstance->error); 
        return $res;
    }
    public function rollback()
    {
        $res = $this->dbInstance->rollback();
        if (!$res) $this->errLog($this->dbInstance->errno, $this->dbInstance->error);
        return $res;
    }

    private function query($sql)
    {
        $res = $this->dbInstance->query($sql);
        if (!$res) $this->errLog($this->dbInstance->errno, $this->dbInstance->error,$sql);
        return $res;
    }
    private function errLog($no, $msg, $sql='')
    {
        $errMsg = $date("Y-m-d H:i:s")."|$no|$msg|$sql\n";
        error_log($errMsg, 3, $this->errLogFile);
    }
    private function parseWhere($where)
    {
        if (is_array($where) && $where) {
            $str = '';
            foreach($where as $k=>$v) {
                if (is_array($v)) {
                    foreach($v as $ck=>$cv) {
                        if (is_array($cv) && in_array($ck, $this->compare)) $str .= "$k $ck".$this->formatArrayIn($cv).' and ';
                        else $str = "$k $ck '".$this->formatString($cv)."' and ";
                    }
                }else if ('NULL' == $v) {
                    $str .= "$k is NULL and ";
                } else if ('NOT NULL' == $v) {
                    $str .= "$k is NOT NULL and ";
                } else {
                    $str .= "$k='".$this->formatString($v)."' and ";
                }
            }
            return ' WHERE '.rtrim($str, 'and ');
        } else {
            return ' ';
        }
    }
    public function formatString($str)
    {
        if (get_magic_quotes_gpc($str)) $str = stripslashes($str);
        if (!is_numeric($str)) $str = $this->dbInstance->real_escape_string($str);
        return $str;
    }
    public function formatArrayIn($arr)
    {
        $str = '(';
        foreach($arr as $v)
            $str .= "'".$this->formatString($v)."',"; 
        return trim($str,',').')';
    }
}

