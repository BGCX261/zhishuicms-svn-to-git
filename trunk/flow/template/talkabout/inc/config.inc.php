<?php
session_cache_expire(2160);
@session_start();
require('mysql.inc.php');
Global $db;
$db = new DB_Sql;
$db->query('set names gbk');
require('function.inc.php');

?>