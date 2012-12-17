<?php

/** Database settings ! */
$mysql_hostname = "localhost";
$mysql_user = "";
$mysql_password = "";
$mysql_database = "";
$prefix = "";


/** Database connection ! */
$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Could not connect database");
if (!empty($prefix)){
	$mysql_database = $prefix.'_'.$mysql_database;
}
mysql_select_db($mysql_database, $bd) or die("Could not select database");
mysql_query("SET NAMES 'utf8';",$bd);

/** A few settings... */
$c_path = 'photos/';
$allowed_ext = array('jpg', 'png', 'gif');

/** Authentification constants ! */
define('COOKIE_NAME', 'gallery-contest');
define('PASSWD', 'admin');
define('HASH', 'DFKJJLkllù*!kdfjgsdDg45+654eytsdfg5d4h@');
?>