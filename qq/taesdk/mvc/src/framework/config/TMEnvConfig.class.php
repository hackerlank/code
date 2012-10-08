<?php
/*
 *---------------------------------------------------------------------------
*
*                  T E N C E N T   P R O P R I E T A R Y
*
*     COPYRIGHT (c)  2009 BY  TENCENT  CORPORATION.  ALL RIGHTS
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
 * TMEnvConfig
 * 环境相关配置
 *
 * @package sdk.mvc.src.framework.config
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMEnvConfig.class.php 2011-11-9 by ianzhang
 */
class TMEnvConfig {

    const ENV_TYPE_TEST = "test";
    const ENV_TYPE_PRODUCTION = "production";
    const ENV_TYPE_BETA = "beta";

    protected static $env;

    protected static $envTypes = array(
            self::ENV_TYPE_TEST => "test",
            self::ENV_TYPE_BETA => "beta",
            self::ENV_TYPE_PRODUCTION => "production"
        );

    public static function envType($mixed)
    {
        return self::$envTypes[$mixed];
    }

    public static function handle()
    {
        $env = self::getEnv();
        if($env == self::envType(self::ENV_TYPE_TEST))
        {
            self::handleTestEnv();
        }else if($env == self::envType(self::ENV_TYPE_BETA))
        {
            self::handleBetaEnv();
        }else if($env == self::envType(self::ENV_TYPE_PRODUCTION))
        {
            self::handleProdctionEnv();
        }
    }

    public static function handleTestEnv()
    {
        TMSqlDebugger::setNeedAddToDebugger(true);
        TMSqlDebugger::setNeedExplainSql(true);
        TMActionTrackDebugger::setNeedAddToDebugger(true);
        TMLogDebugger::setNeedAddToDebugger(true);

        TMException::setNeedShowrealMsg(true);

        TMSecurityFilter::setEnableSecurity(false);
        TMTrackFilter::setEnableAddTrackCode(false);

        TMHook::setNeedShowExceptionMsg(true);

        TMLog::setNeedThrowException(true);

        TaeIMService::setIsTestENV(true);
        TaeMonitorService::setEnableReport(false);
    }

    public static function handleBetaEnv()
    {
        TMSqlDebugger::setNeedAddToDebugger(true);
        TMSqlDebugger::setNeedExplainSql(true);
        TMActionTrackDebugger::setNeedAddToDebugger(true);
        TMLogDebugger::setNeedAddToDebugger(true);

        TMException::setNeedShowrealMsg(true);

        TMSecurityFilter::setEnableSecurity(false);

        TMHook::setNeedShowExceptionMsg(true);

        TMLog::setNeedThrowException(true);
        TaeMonitorService::setEnableReport(false);
    }

    public static function handleProdctionEnv()
    {

    }

    public static function initEnv()
    {
        if(file_exists(ROOT_PATH . 'config/env.php')) {
            include ROOT_PATH . 'config/env.php';
        }

        if(! isset( $_ENV['SERVER_TYPE'] )) {
            if( isset($_SERVER['SERVER_TYPE'] ) ) {
                $_ENV['SERVER_TYPE'] = $_SERVER['SERVER_TYPE'];
            }
            else {
                $_ENV['SERVER_TYPE'] = self::envType(self::ENV_TYPE_PRODUCTION);
            }
        }

        self::$env = $_ENV['SERVER_TYPE'];
    }

    public static function getEnv()
    {
        if(empty(self::$env))
        {
            self::initEnv();
        }

        return self::$env;
    }
}