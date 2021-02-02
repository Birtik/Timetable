<?php

require('Connect.php');

$db_host = "127.0.0.1:3310";
$db_name = "ex0";
$db_user = "root";
$db_pass = "";

try{
    $conn = new PDO(Connect::getDSN($db_name,$db_host),$db_user,$db_pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"));

    return $conn;
} 
catch(PDOException $e){
    echo 'Connection failed!'. $e->getMessage();
}


?>