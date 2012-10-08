#!/usr/local/php/bin/php
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
define("ROOT_PATH", realpath(dirname(__FILE__) ) . '/');
define('LIB_PATH', '/usr/local/taesdk/1.1/phplib3/src/');
define('FRAMEWORK_PATH', '/usr/local/taesdk/1.1/mvc/src/framework/');
define("CACHE_PATH", ROOT_PATH. 'cache/');

set_include_path(get_include_path() . PATH_SEPARATOR .FRAMEWORK_PATH. PATH_SEPARATOR. LIB_PATH);

require_once "base/core/TMAutoload.class.php";
TMAutoload::getInstance()->setSavePath(CACHE_PATH.'autoload/')
->setDirs(array(ROOT_PATH, LIB_PATH, FRAMEWORK_PATH))->execute();

TMConfig::initialize(false);

include(FRAMEWORK_PATH.'/command/cli.php');