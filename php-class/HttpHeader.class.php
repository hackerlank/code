<?php
	/**
	@author:shenjian
	@email:xiaoshengeer@gmail.com
	@create:2012-05-09 11:05:00
	@encoding:utf8 sw=4 ts=4
	**/
class HttpHeader
{
    public static function download($filename, $data)
    {
        header('Content-Disposition: attachment; filename=' . urlencode($filename));   
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/download');
        header('Content-Description: File Transfer');            
        echo $data;
    }
    public static function auth($name, $pwd)
    {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
             $_SERVER['PHP_AUTH_USER'] != $name ||$_SERVER['PHP_AUTH_PW'] != $pwd) {
            Header("WWW-Authenticate: Basic realm=\"Login\"");
            Header("HTTP/1.0 401 Unauthorized");
  
            echo "<html><body><h1>Rejected!</h1><big>Wrong Username or Password!</big></body></html>";
            exit;
        }
    }
    public static function noCache()
    {
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: pre-check=0, post-check=0, max-age=0');
        header ('Pragma: no-cache');
    }
    public static function charset($charset='utf-8')
    {
        header("Content-type: text/html; charset=utf-8"); 
    }
    public static function redirect($url)
    {
        header('Location: '.$url);
        die();
    }
}
