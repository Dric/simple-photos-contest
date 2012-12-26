<?php 
DEFINE('SPC_VERSION', '1.1');
$sql=mysql_query("SELECT * FROM settings");
$settings = mysql_fetch_object($sql);

if (!empty($settings)){
	/** Translations ! */

	/** Define language used
	*
	* To see the locales installed in your ubuntu server, type locale -a in shell.
	*/
	putenv("LC_ALL=".$settings->language);
	setlocale(LC_ALL, $settings->language);
	bindtextdomain("messages", "lang");
	bind_textdomain_codeset('messages', 'UTF-8');
	textdomain("messages");

}

/** Are we authentified ? */
$admin_logged = admin_logged();


/**
* Transcrit une date au format sql en format français.
*
* @param string $mysql_date DATE ou DATETIME SQL
* @param string $time Si valeur égale à 'notime' (valeur par défaut), on ne retourne pas les heures:minutes:secondes.
*/
function date_formatting($mysql_date, $to_sql = false){
	global $settings;
  if (!$to_sql){
    //return date_format(date_create($mysql_date), $settings->date_format);
		return changeDateFormat($mysql_date, 'Y-m-d', $settings->date_format);
  }else{
    //return date_format(date_create_from_format($settings->date_format, $mysql_date), 'Y-m-d');
		return changeDateFormat($mysql_date, $settings->date_format, 'Y-m-d');
  }
}
/** For php version < 5.3 
* http://php.net/manual/en/function.date.php#90423
*/
function dateParseFromFormat($stFormat, $stData)
 {
     $aDataRet = array('day'=>0, 'month'=>0, 'year'=>0, 'hour'=>0, 'minute'=>0, 'second'=>0);
     $aPieces = split('[:/.\ \-]', $stFormat);
     $aDatePart = split('[:/.\ \-]', $stData);
     foreach($aPieces as $key=>$chPiece)    
     {
         switch ($chPiece)
         {
             case 'd':
             case 'j':
                 $aDataRet['day'] = $aDatePart[$key];
                 break;
                 
             case 'F':
             case 'M':
             case 'm':
             case 'n':
                 $aDataRet['month'] = $aDatePart[$key];
                 break;
                 
             case 'o':
             case 'Y':
             case 'y':
                 $aDataRet['year'] = $aDatePart[$key];
                 break;
             
             case 'g':
             case 'G':
             case 'h':
             case 'H':
                 $aDataRet['hour'] = $aDatePart[$key];
                 break;    
                 
             case 'i':
                 $aDataRet['minute'] = $aDatePart[$key];
                 break;
                 
             case 's':
                 $aDataRet['second'] = $aDatePart[$key];
                 break;            
         }
         
     }
     return $aDataRet;
 }
 
 function changeDateFormat($stDate,$stFormatFrom,$stFormatTo)
 {
   // When PHP 5.3.0 becomes available to me
   //$date = date_parse_from_format($stFormatFrom,$stDate);
   //For now I use the function above
   $date = dateParseFromFormat($stFormatFrom,$stDate);
   return date($stFormatTo,mktime($date['hour'],
                                     $date['minute'],
                                     $date['second'],
                                     $date['month'],
                                     $date['day'],
                                     $date['year']));
 }

/**
* Valide une date suivant un format.
* 
* @param string $date Date à valider
* @param string $format Format que doit avoir la date à valider
*/
function date_valid($date, $format = null) {
	global $settings;
	if (empty($format)){
		$format = $settings->date_format;
	}
   if (date($format, strtotime($date)) == $date) {
       return true;
   } else {
       return false;
   }
 }
 
/**
 * Permet de savoir si l'admin est connecté.
 *
 * @return bool
 */
function admin_logged(){
  if (isset($_COOKIE[COOKIE_NAME])){
    $cookie = $_COOKIE[COOKIE_NAME];
    if ($cookie == sha1(PASSWD.HASH)){
			return true;
		}
  }
	return false;
}
/**
 * Affiche un message de notification.
 *
 * @param object $message
 */
function disp_message($message){
	if (isset($message->type)){
		return '<div class="alert '.$message->type.'"><a class="close" href="#" title="Fermer">×</a>'.$message->text.'</div>';
	}
}

function info_disp($message){
	return '<img alt="'.$message.'" class="img_info" src="img/info.png" />';
}

/**
* Display a vertical bar graph with contest votes.
* @param string $contest Contest ID
* 
*/
function contest_stats($contest){
	global $settings, $c_path;
	/** Get contest data. */
	$sql=mysql_query('SELECT * FROM contests WHERE contest = "'.$contest.'"');
	$cont = mysql_fetch_object($sql);
	/** Get images and votes. */
	$sql=mysql_query('SELECT *FROM images WHERE contest = "'.$contest.'" ORDER BY img_name');
	$nbphotos = mysql_num_rows($sql);
	/** Get number of voters (format : array with only first value populated) */
	$nbvoters = mysql_fetch_row(mysql_query('SELECT COUNT(DISTINCT ip_add) FROM image_IP WHERE contest = "'.$contest.'"'));
	$nbvoters = $nbvoters[0];
	/** Let's build the graph source js array ! */
	?>
	<script>
		if (typeof arrayOfData == "undefined") {
			arrayOfData = new Array();
		}
		arrayOfData[<?php echo $contest; ?>] = new Array(
	<?php
	$disp = '';
	$nbvotes = 0;
	/** @param array Photo name as first value, img url as second and number of votes as third. */
	$mostvoted = array(0,0,0);
	while($row=mysql_fetch_array($sql)){
		$disp .= '['.$row['love'].', "<a title=\"'.$row['img_name'].'\" href=\"'.$c_path.$contest.'/'.$row['img_url'].'\" class=\"lightbox\">'.$row['img_name'].'</a>"],';
		$nbvotes += $row['love'];
		if ($mostvoted[2] < $row['love']){
			$mostvoted[0] = $row['img_name'];
			$mostvoted[1] = $row['img_url'];
			$mostvoted[2] = $row['love'];
		}
	}
	$disp = trim($disp, ',');
	echo $disp;
	?>
		);
	</script>
	<div class="graph" data-contest="<?php echo $contest; ?>" data-title="<h2><?php echo sprintf(_('%s contest'), $cont->contest_name); ?></h2>" id="contest_graph_<?php echo $contest; ?>"></div>
	<ul class="stats_data">
		<li><?php echo _('Number of votes'); ?> : <span class="stats_numbers"><?php echo $nbvotes; ?></span></li>
		<li><?php echo _('Number of voters'); ?> : <span class="stats_numbers"><?php echo $nbvoters; ?></span></li>
		<li><?php echo _('Number of photos'); ?> : <span class="stats_numbers"><?php echo $nbphotos; ?></span></li>
		<li><?php echo _('Favorite'); ?> : <span class="stats_numbers"><a class="lightbox" href="<?php echo $c_path.$contest.'/'.$mostvoted[1]; ?>" title="<?php echo $mostvoted[0]; ?>"><?php echo $mostvoted[0]; ?></a></span> <?php echo _('with'); ?> <span class="stats_numbers"><?php echo $mostvoted[2]; ?></span> <?php echo ngettext('vote', 'votes', $mostvoted[2]); ?></li>
	</ul>
	<?php
}
?>