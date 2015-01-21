<?php

/** Database settings ! */
$mysql_hostname = "localhost";
$mysql_user = "";
$mysql_password = "";
$mysql_database = "";
$prefix = "";


/** Database connection ! */
if (!empty($prefix)){
	$mysql_database = $prefix.'_'.$mysql_database;
}

$bd = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
if ($bd === false or mysqli_connect_error()){
	die("Could not connect to database. Please verify your settings in <code>config.php</code> file.<br>Error ".mysqli_connect_errno()." : <code>".mysqli_connect_error()."</code>");
}
mysqli_query($bd, "SET NAMES 'utf8';");

/** A few settings... */
$c_path = 'photos/';
$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

/** Authentification constants ! */
define('COOKIE_NAME', 'gallery-contest');
define('PASSWD', 'admin');
define('HASH', 'DFKJJLkllù*!kdfjgJHhvkjJK5263/$ytsdfg5d4h@');
?>