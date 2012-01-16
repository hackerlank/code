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
define( 'PL2_SESS_NAME_UIN' , 'uin');
define( 'PL2_SESS_NAME_KEY' , 'skey');
define( 'PL2_LSESS_NAME_UIN' , 'luin');
define( 'PL2_LSESS_NAME_KEY' , 'lskey');
define( 'PL2_VC_NAME_SESSION', "verifysession");

if (isset($_ENV['SERVER_TYPE']) && $_ENV['SERVER_TYPE'] == 'test' )
{
    //开发机模式
    define( 'PL2_VKEY_SERVER1_HOST', '172.25.38.16' );
    define( 'PL2_VKEY_SERVER1_PORT', 58001 );
    define( 'PL2_VKEY_SERVER2_HOST', '172.25.38.16' );
    define( 'PL2_VKEY_SERVER2_PORT', 58001 );

    define( 'PL2_SESS_SERVER1_HOST', '172.25.38.16' );
    define( 'PL2_SESS_SERVER1_PORT', 58000 );
    define( 'PL2_SESS_SERVER2_HOST', '172.25.38.16' );
    define( 'PL2_SESS_SERVER2_PORT', 58000 );
} else {
    //运营机模式
    define( 'PL2_VKEY_SERVER1_HOST', '172.23.32.42' );
    define( 'PL2_VKEY_SERVER1_PORT', 18888 );
    define( 'PL2_VKEY_SERVER2_HOST', '172.23.32.44' );
    define( 'PL2_VKEY_SERVER2_PORT', 18888 );

    define( 'PL2_SESS_SERVER1_HOST', '172.16.85.48' );
    define( 'PL2_SESS_SERVER1_PORT', 18891 );
    define( 'PL2_SESS_SERVER2_HOST', '172.27.10.9' );
    define( 'PL2_SESS_SERVER2_PORT', 18891 );
}
define( 'PL2_VKEY_VALIDTIME', 1800 );
?>