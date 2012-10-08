<?php
return array (
  'db' => 
  array (
    'default' => 
    array (
      'host' => 'localhost',
      'username' => 'root',
      'password' => '',
    ),
    'actdbbak' => 
    array (
      'host' => '172.25.38.31',
      'username' => 'visitor',
      'password' => '11636971241013664124100124971241161249736',
    ),
    'userdbbak' => 
    array (
      'host' => '172.25.38.31',
      'username' => 'visitor',
      'password' => '11636971241013664124100124971241161249736',
    ),
  ),
  'memcache' => 
  array (
    'enable' => true,
    'server' => 
    array (
      0 => 
      array (
        'host' => 'localhost',
        'port' => 11211,
      ),
    ),
    'persistent' => false,
  ),
  'tae' => 
  array (
    'server' => 
    array (
      0 => 
      array (
        'host' => '124.115.12.45',
        'port' => 26000,
      ),
    ),
    'fastcgi' => 
    array (
      0 => 
      array (
        'host' => '172.25.38.31',
        'vhost' => '',
      ),
    ),
  ),
  'track' => 
  array (
    'url' => 'http://act.t.l.qq.com',
    'host_ip' => '10.1.164.19:8081',
  ),
  'udsinfo' => 
  array (
    'path' => 'http://emarketing.qq.com/cgi-bin/nick/getUDSInfo',
  ),
  'weibo' => 
  array (
    'tae' => 
    array (
    ),
    'weiboServer' => 
    array (
      0 => 
      array (
        'host' => '10.137.224.26:8080',
        'vhost' => 'api.t.qq.com',
      ),
    ),
  ),
  'debug_mode' => true,
  'namespace' => 'laneige',
  'tams_id' => 641009454,
  'appid' => 4008401,
  'domain' => 'test.qq.com',
  'base_url' => 'http://test.qq.com/',
  'dbname' => 'Vendor_DB_Laneige2011',
  'error_log' => 
  array (
    'path' => 'log/error_log',
    'size' => 134217728,
  ),
  'filter_esc_enable' => true,
  'hook' => false,
  'mysqlStringColumns' => 
  array (
    0 => 'FQQ',
    1 => 'FDesId',
    2 => 'FSrcId',
    3 => 'FValue1',
    4 => 'FSrcQQ',
    5 => 'FDesQQ',
    6 => 'FJoinQQ',
    7 => 'FInviteQQ',
  ),
  'components' => 
  array (
  ),
  'template_path' => 
  array (
    'layout' => 'templates/layout/',
    'template' => 'templates/',
  ),
  'layout' => 
  array (
    'enable' => false,
    'default' => '',
  ),
  'controller' => 
  array (
    'key' => 'controller',
    'default_name' => 'default',
  ),
  'action' => 
  array (
    'key' => 'action',
    'default_name' => 'default',
  ),
  'component' => 
  array (
    'key' => 'component',
  ),
);