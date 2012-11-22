<?php
$mysql_hostname = "localhost";
$mysql_user = "";
$mysql_password = "";
$mysql_database = "";
$prefix = "";
$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Could not connect database");
mysql_select_db($mysql_database, $bd) or die("Could not select database");
mysql_query("SET NAMES 'utf8';",$bd);

$c_path = 'photos/';
$max_value = 250;
$default_contest = '2013';
$allowed_ext = array('jpg', 'png', 'gif', 'bmp');

?>