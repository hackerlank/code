<?php

function write_error_log_file($errno, $errstr, $errfile, $errline)
{
    $error_log_file = "/tmp/php_error_log";
    $error_constants = array(
        1 => 'E_ERROR',
        2 => 'E_WARRING',
        4 => 'E_PARSE',
        8 => 'E_NOTICE',
    );
    $str = date("Y-m-d H:i:s").' '.$error_constants[$errno].":".$errstr." in ".$errfile." on line ".$errline."\n";
    error_log($str, 3, $error_log_file);;
}

//catch fatal errors
/*
//anothors is also working
//ref:http://huoding.com/2012/05/31/151

ob_start(function($buffer) {
    if ($error = error_get_last()) {
        return var_export($error, true);
    }

    return $buffer;
});
//ob_start + error_get_last
//register_shutdown_function + error_get_last
*/
register_shutdown_function(
    function()
    {
        if($error = error_get_last())
        {
            write_error_log_file($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }
);
//catch notice, warring
set_error_handler("write_error_log_file");
