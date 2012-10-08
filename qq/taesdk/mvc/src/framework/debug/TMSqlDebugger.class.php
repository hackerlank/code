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
 * TMSqlDebugger
 * SQL语句调试器
 *
 * @package sdk.mvc.src.framework.debug
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMSqlDebugger.class.php 2010-12-30 by ianzhang
 */
class TMSqlDebugger extends TMAbstractDebugger {

    protected $sqlLogPath = "log/devsql.log";

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->name = "sql";
    }

    protected static $sqlArray = array();

    protected static $defectSqlArray = array();

    //防止递归做sql检查
    protected static $isAdding = false;

    protected static $needExplainSql = false;

    /**
     * 设置SQL LOG的文件位置
     * @param string $sqlLogPath
     */
    public function setSqlLogPath($sqlLogPath)
    {
        $this->sqlLogPath = $sqlLogPath;
    }

    /**
     * 获得SQL LOG的文件位置
     * @return string
     */
    public function getSqlLogPath()
    {
        return $this->sqlLogPath;
    }

    public static function getNeedExplainSql()
    {
        return self::$needExplainSql;
    }

    public static function setNeedExplainSql($needExplainSql)
    {
        self::$needExplainSql = $needExplainSql;
    }

    /**
     * 将SQL语句加入到DEBUGGER中
     * @param string $sql
     * @param string $time
     * @param TMMysqlAdapter $db
     */
    public function add($sql, $time, $db)
    {
        try{
            $dispatcher = TMDispatcher::getInstance();
            $controllerName = $dispatcher->getController();
            $controllerClassName = $controllerName."Controller";

            $debugArray = debug_backtrace();
            foreach($debugArray as $key => $value)
            {
                $class = isset($value["class"]) ? $value["class"] : "";
                if($class == $controllerClassName)
                {
                    $tmpKey = $key-1;
                    $tmpValue = $debugArray[$tmpKey];
                    $file = $tmpValue["file"];
                    $line = $tmpValue["line"];

                    break;
                }
            }
            $currTime = time();
            $content = "[Time: $currTime][File: $file][Line: $line]".$sql;
            $logSize = 134217728; //128M
            $logSize = (TMConfig::get("error_log", "size") == null) ? $logSize : TMConfig::get("error_log", "size");
            $needUnLink = false;
            if(is_file(ROOT_PATH.$this->sqlLogPath)){
                $filesize = @filesize(ROOT_PATH.$this->sqlLogPath);
                if(!empty($filesize) && $filesize > $logSize)
                {
                    $needUnLink = true;
                }
            }

            if($needUnLink){
                @unlink(ROOT_PATH.$this->sqlLogPath);
            }
            file_put_contents(ROOT_PATH.$this->sqlLogPath, $content."\n", FILE_APPEND);

            if(!self::$isAdding){
                self::$isAdding = true;
                //将sql语句打印到页面上
                $beforeTime = isset(self::$sqlArray[$sql]["time"]) ? self::$sqlArray[$sql]["time"] : 0;
                $beforeCallCount = isset(self::$sqlArray[$sql]["call"]) ? self::$sqlArray[$sql]["call"] : 0;
                self::$sqlArray[$sql]["time"] = $beforeTime + $time;
                self::$sqlArray[$sql]["call"] = $beforeCallCount + 1;

                //做sql语句优化判断
                $this->explainSql($sql, $db);
                self::$isAdding = false;
            }
        }catch(TMService $te)
        {
            //do nothing
        }
    }

    protected function explainSql($sql, $db)
    {
        if(self::$needExplainSql){
            $tmpSql = "";
            if(preg_match("/^update (.+) set.+(where.+)$/i", $sql, $matches))
            {
                $table = $matches[1];
                $whereStr = $matches[2];
                $tmpSql = "explain select * from $table $whereStr";
            }else if(preg_match("/^select/",$sql)){
                $tmpSql = "explain ".$sql;
            }

            if(!empty($tmpSql)){
                $result = $db->query($tmpSql, MYSQLI_ASSOC);

                if (! $result->isEmpty ())
                {
                    $rows = $result->getAllRows ();
                }
                else
                {
                    $rows = array();
                }

                $result = $rows;

                $item = $result[0];
                if(strpos($item["Extra"], "Using filesort") !== FALSE
                        || strpos($item["Extra"], "Using temporary") !== FALSE
                        || (strpos($item["Extra"], "Using where") !== FALSE
                                && ($item["type"] == "ALL" or $item["type"] == "index"))){
                    self::$defectSqlArray[] = $sql;
                }
            }
        }
    }

    public static function getDebuggerSqls()
    {
        return self::$sqlArray;
    }

    public static function getExplainResults()
    {
        return self::$defectSqlArray;
    }
}
?>
