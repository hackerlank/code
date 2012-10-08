<?php
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL);
$files = file('iplists.txt');
$mysqli = new mysqli('localhost','root','root','test');
if (mysqli_connect_error()) {
    die('error:['.mysqli_connect_errno.']'.mysqli_connect_error());
}
foreach ($files as $row){
    $res = explode("|",$row);
    if(!isset($res[2])) $res[2]='未知';
    else $res[2] = $mysqli->escape_string($res[2]);

    if(!isset($res[3])) $detail='0';
    else {
        $detail = '';
        for($i=3;$i<count($res);$i++)
            $detail .= $mysqli->escape_string($res[$i]);
    }

    $mysqli->query("set names utf8");
    $sqlstr = "INSERT INTO iplists(`startip`,`endip`,`area`,`type`)
               VALUES('{$res[0]}','$res[1]','{$res[2]}','{$detail}')";
    if(!$mysqli->query($sqlstr)) print_r($res);
}
echo "ok\n\r";
?>
