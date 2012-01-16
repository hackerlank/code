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
 * TMConfig
 * The application config
 *
 * @package sdk.mvc.src.framework.config
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMConfig.class.php 2008-9-11 by ianzhang
 */
class TMConfig {

    protected static $env;

    protected static $etcYmlPath = "/etc/app/default.yml";

    /**
     * 获得全局机器配置文件的地址
     * @return string
     */
    public static function getEtcYmlPath()
    {
        return self::$etcYmlPath;
    }

    /**
     * 获得实例化对象
     *
     * @param boolean $needInitLog
     */
    public static function initialize($needInitLog = true)
    {
        self::checkEnv();

        self::initConfigYaml();

        if($needInitLog){
            $log = new TMLog();
            TMDebugUtils::addLogger($log);
            TMDebugUtils::setDebugMode(TMConfig::get("debug_mode"));
            TMException::addLogger($log);
        }

        self::handleEnvConfig();
    }

    /**
     * 初始化yaml配置文件
     */
    protected function initConfigYaml()
    {
        $cacheFile = ROOT_PATH."cache/config/config_all.php";
        $env = self::$env;
        if (!file_exists($cacheFile)
            || ($env != "test" && filemtime(ROOT_PATH."config/config_{$env}.yml") > filemtime($cacheFile))
            || filemtime(ROOT_PATH."config/config.yml") > filemtime($cacheFile)
            || filemtime(ROOT_PATH."config/render.yml") > filemtime($cacheFile)
            || filemtime(self::$etcYmlPath) > filemtime($cacheFile))
        {
            $configEtcArray = TMBasicConfigHandle::getInstance()->execute(self::$etcYmlPath);
            self::set($configEtcArray);

            $configArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/config.yml");
            self::set($configArray);

            $renderConfigArray = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH."config/render.yml");

            self::set($renderConfigArray);

            if($env != "test")
            {
                $configEnvArray = TMBasicConfigHandle::getInstance()
                    ->execute(ROOT_PATH."config/config_{$env}.yml");
                self::set($configEnvArray);
            }

            $input = TMRegisterTree::getAll();
            $content = "<?php\nreturn ".var_export($input, true).';';

            $path = "config/";
            $name = "config_all.php";
            TMFileCache::getInstance()->execute($path, $name, $content);
        }
        else{
            $configArray = include $cacheFile;
            self::set($configArray);
        }
    }

    /**
     * 设置配置数组
     * @param array $configArray
     */
    public static function set($configArray)
    {
        if(!empty($configArray)){
            $allConfigArray = TMRegisterTree::getAll();
            $allConfigArray = array_merge($allConfigArray, $configArray);
            TMRegisterTree::setAll($allConfigArray);
        }
    }

    /**
     * 获取对应key的配置
     * @return mixed
     */
    public static function get()
    {
        $paraArray = func_get_args ();
        return call_user_func_array(array("TMRegisterTree", "get"), $paraArray);
    }

    /**
     * 检查代码所处的环境
     */
    protected static function checkEnv()
    {
        self::$env = TMEnvConfig::getEnv();
    }

    protected static function handleEnvConfig()
    {
        TMEnvConfig::handle();
    }

    /**
     * 实现的魔术调用方法
     * @param string $method
     * @param array $arguments
     * @return mixed $result
     */
    public function __call($method, $arguments = array())
    {
        $searchKeyArray[] = $method;

        $searchKeyArray = array_merge($searchKeyArray, $arguments);

        $tmp = self::$configArray;
        foreach($searchKeyArray as $searchKey)
        {
            if(!isset($tmp[$searchKey]))
            {
                return null;
            }
            $tmp = $tmp[$searchKey];
        }

        if(!isset($tmp))
        {
            return null;
        }

        return $tmp;
    }
}
