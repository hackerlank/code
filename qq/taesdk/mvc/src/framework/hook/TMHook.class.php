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
 * 钩子类
 *
 * <ul>
 * <li>钩子也可以理解为事件，就是在执行某个动作之前或者执行某个动作之后，触发某一个、某一串连续的动作</li>
 * <li>也有人说Hook其实就是在某些地方开了一个后门，在需要的时候从这个后门进去做一些事情</li>
 * <li>配置：config/hooks.yml，</li>
 * <li>其中每个hook event可以配置任意多个action，</li>
 * <li>每个action必须包含class和function，static和data为选填项</li>
 * <li>static是指要触发的方法是否静态方法，1:是0:不是，默认不是</li>
 * <li>data是指需要传递给触发方法的额外的静态参数，数组格式，将合并到触发点传入的参数后传给被触发的action</li>
 * </ul>
 *
 * <code>
 * before_sql_execute:
 *  - {class: Demo, function: profileIn, static: 1, data: ['sql_execute']}
 * after_sql_execute:
 *  - {class: Demo, function: profileOut, static: 1, data: ['sql_execute']}
 * </code>
 *
 * 触发的action
 * <code>
 *    class Demo {
 *        // @param string $name 从触发点传入的参数-SQL语句
 *        // @param string $p 从配置中传入的固定参数
 *        public static function profileIn($name, $p)
 *        {
 *            return TMProfile::in($name . " " . $p);
 *        }
 *        public static function profileOut($name, $p)
 *        {
 *            return TMProfile::out($name . " " . $p);
 *        }
 *    }
 * </code>
 *
 * 调用: see <code>TMMysqlAdapter::query</code>
 * <code>
 *     public function query($SQL)
 *     {
 *         TMDebugUtils::debugLog($SQL);
 *         TMHook::call("before_sql_execute", array($SQL)); //在SQL开始执行以前
 *         $result = mysqli_query ($this->connection, $SQL);
 *         $this->affectedRowNum = mysqli_affected_rows($this->connection);
 *         $this->insertId = mysqli_insert_id($this->connection);
 *         TMHook::call("after_sql_execute", array($SQL)); //在SQL执行完以后
 *         return new TMMysqlResult ($result);
 *     }
 * </code>
 *
 * @package sdk.mvc.src.framework.hook
 * @author  lynkli <lynkli@tencent.com>
 * @version TMHook.class.php 2009-11-05 by lynkli
 */
class TMHook
{
    private static $configs = null;

    protected static $needShowExceptionMsg = false;

    public static function setNeedShowExceptionMsg($needShowExceptionMsg)
    {
        self::$needShowExceptionMsg = $needShowExceptionMsg;
    }

    public static function getNeedShowExceptionMsg()
    {
        return self::$needShowExceptionMsg;
    }

    /**
     * 调用hook
     *
     * 根据 config/hook.yml 中对各个事件的配置，调用对应的方法
     *
     * @param string $event 事件（也可以理解为hook位置）
     * @param array $params 事件参数，键值对
     * @return boolean 如果返回false，则表示hook处理异常或者失败，如果返回true，表示该hook处理完成。
     */
    public static function call($event, $params=array())
    {
        //如果hook功能没有开放
        if (!TMConfig::get("hook"))
        {
            return true;
        }

        //获取hook配置
        if (self::$configs === null)
        {
            self::$configs = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH . "config/hook.yml");
        }
        $hookConfig = self::$configs;

        //如果当前事件没有配置hook
        if (empty($hookConfig) || empty($hookConfig[$event]))
        {
            return true;
        }
        $hooks = $hookConfig[$event];

        $result = array();
        foreach ($hooks as $config)
        {
            //如果hook类或者hook方法为空
            if (empty($config['class']) || empty($config['function']))
            {
                self::onError('Hook Config Error');
                return false;
            }

            $class = $config['class'];
            //如果hook类为空
            if (!class_exists($class))
            {
                self::onError("Hook Class does't exist");
                return false;
            }

            $function = $config['function'];
            if (isset($config['static']) && $config['static'] == "1")
            {
                $obj = $class;
            }
            else
            {
                $obj = new $class();
            }

            //如果hook方法不存在
            if (!method_exists($obj, $function))
            {
                self::onError("Hook Function does't exist");
                return false;
            }
            else
            {
                //加入静态额外参数
                $data = !isset($config, $config['data']) ? array() : $config['data'];
                if (!is_array($data))
                {
                    $data = array();
                }
                foreach ($data as $d)
                {
                    $params[] = $d;
                }

                //执行hook方法
                $r = call_user_func_array(array($obj, $function), $params);

                //如果返回false
                if ($r === false)
                {
                    self::onError("Hook Function failed");
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * hook 错误处理
     *
     * 在测试环境中，发生hook错误的时候，会抛出TMHookException并将相应信息记录到日志中<br>
     * 在正式环境中，发生hook错误的时候，不会抛出异常，但会记录到错误日志中。
     *
     * @param string $message hook异常信息
     * @throws TMHookException
     */
    private static function onError($message)
    {
        $log = new TMLog();
        $log->ll("Hook Exception: " . $message);

        if(self::$needShowExceptionMsg)
        {
            throw new TMHookException($message);
        }
    }
}