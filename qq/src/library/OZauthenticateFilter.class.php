<?php
/**
 * @author: xiaoshengeer@gmail.com
 */
class OZauthenticateFilter extends TMFilter
{
    
    public function execute($filterChain)
    {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
        $_SERVER['PHP_AUTH_USER'] != "admin" || $_SERVER['PHP_AUTH_PW'] != "admin"){
            Header("WWW-Authenticate: Basic realm=\"please login\"");
			Header("HTTP/1.0 401 Unauthorized");
			
			echo "<html><body>Wrong UserName or Password!</body></html>";
			exit;
        }
        $filterChain->execute();
    }
}