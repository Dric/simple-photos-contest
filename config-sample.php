<?php
/** Database settings ! */
$mysql_hostname = "localhost";
$mysql_user = "";
$mysql_password = "";
$mysql_database = "";
$prefix = "";


/** Database connection ! */
$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Could not connect database");
mysql_select_db($mysql_database, $bd) or die("Could not select database");
mysql_query("SET NAMES 'utf8';",$bd);

/** A few settings... */
$c_path = 'photos/';
$max_value = 250;
$default_contest = '2013';
$allowed_ext = array('jpg', 'png', 'gif');

/** Authentification constants ! */
define('COOKIE_NAME', 'gallery-contest');
define('PASSWD', 'admin');
define('HASH', 'DFKJJLkllù*!kdfjgsdDg45+654eytsdfg5d4h@');


/** Translations ! */

/** Define language used
*
* To see the locales installed in your ubuntu server, type locale -a in shell.
*/
define('LANG','en_US.utf8');

/** Date format
*
* See http://php.net/manual/en/function.date.php for parameters
* A few examples :
* French : 'd/m/Y'
* USA : 'm/d/Y'
*/
define('DATE_FORMAT', 'm/d/Y');

/** Nothing to do below */
putenv("LC_ALL=".LANG);
setlocale(LC_ALL, LANG);
bindtextdomain("messages", "lang");
bind_textdomain_codeset('messages', 'UTF-8');
textdomain("messages");

?>