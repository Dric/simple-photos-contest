<?php 
/**
* Transcrit une date au format sql en format français.
*
* @param string $mysql_date DATE ou DATETIME SQL
* @param string $time Si valeur égale à 'notime' (valeur par défaut), on ne retourne pas les heures:minutes:secondes.
*/
function date_french($mysql_date, $to_sql = false){
  if (!$to_sql){
    return date_format(date_create($mysql_date), 'd/m/Y');
  }else{
    return date_format(date_create_from_format('d/m/Y', $mysql_date), 'Y-m-d');
  }
}

/**
* Valide une date suivant un format.
* 
* [http://www.php.net/manual/fr/function.checkdate.php#107569]
*
* @param string $date Date à valider
* @param string $format Format que doit avoir la date à valider
*/
function date_valid($date, $format = 'DD/MM/YYYY'){
  if(strlen($date) >= 8 && strlen($date) <= 10){
    $separator_only = str_replace(array('M','D','Y'),'', $format);
    $separator = $separator_only[0];
    if($separator){
      $regexp = str_replace($separator, "\\" . $separator, $format);
      $regexp = str_replace('MM', '(0[1-9]|1[0-2])', $regexp);
      $regexp = str_replace('M', '(0?[1-9]|1[0-2])', $regexp);
      $regexp = str_replace('DD', '(0[1-9]|[1-2][0-9]|3[0-1])', $regexp);
      $regexp = str_replace('D', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
      $regexp = str_replace('YYYY', '\d{4}', $regexp);
      $regexp = str_replace('YY', '\d{2}', $regexp);
      if($regexp != $date && preg_match('/'.$regexp.'$/', $date)){
        foreach (array_combine(explode($separator,$format), explode($separator,$date)) as $key=>$value) {
          if ($key == 'YY') $year = '20'.$value;
          if ($key == 'YYYY') $year = $value;
          if ($key[0] == 'M') $month = $value;
          if ($key[0] == 'D') $day = $value;
        }
        if (checkdate($month,$day,$year)) return true;
      }
    }
  }
  return false;
}

function disp_message($message){
	if (isset($message->type)){
		return '<div class="alert '.$message->type.'"><a class="close" href="#" title="Fermer">×</a>'.$message->text.'</div>';
	}
}
?>