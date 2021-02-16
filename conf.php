<?php

require('Class\Connect.php');

const DB_HOST = "127.0.0.1:3310";
const DB_NAME = "ex0";
const DB_USER = "root";
const DB_PASS = "";


try{
    return new PDO(Connect::getDSN(DB_NAME,DB_HOST),DB_USER,DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"));
} 
catch(PDOException $e){
    echo 'Connection failed!'. $e->getMessage();
}
