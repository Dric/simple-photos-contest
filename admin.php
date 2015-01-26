<?php
include('config.php');
include('functions.php');

/** Prevent unauthorized access to admin panel. */
if (!$admin_logged){
	die('Nice tried, but your are not logged in.');
}

/** List of db recorded contests. */
$contests = array();
/** Notification message init. */
$message = new stdClass;


if (isset($_GET['tab']) and !empty($_GET['tab']) and $_GET['tab'] != 'stats'){
	$tab = htmlspecialchars($_GET['tab']); 
}else{
	$tab = 'contests';
}

if (isset($_GET['contest']) and !empty($_GET['contest']) and $tab == 'contests'){
	$contest = htmlspecialchars($_GET['contest']);
	if (isset($_GET['action'])){
		$action = htmlspecialchars($_GET['action']);
	} elseif(isset($_POST['action'])){
		$action = htmlspecialchars($_POST['action']);
	} elseif(isset($_POST['save'])){
		$action = 'save';
	} elseif(isset($_POST['del'])){
		$action = 'del';
	} else{
		$action = null;
	}
	switch($action){
		case 'save':
			/** We save contest setttings in db. */
			$contest_name = htmlspecialchars($_POST['contest_name']);
			$desc = htmlspecialchars($_POST['description']);
			$date_begin = date_formatting(htmlspecialchars($_POST['date_begin']), true);
			$date_end = date_formatting(htmlspecialchars($_POST['date_end']), true);
      $voting_type = htmlspecialchars($_POST['voting_type']);
			if ($desc == ''){
				$desc = 'NULL';
			}else{
				$desc = "'".$desc."'";
			}
			$query = "UPDATE contests SET contest_name = '".$contest_name."', description = ".$desc.", date_begin = '".$date_begin."', date_end = '".$date_end."', voting_type = '".$voting_type."' WHERE contests.contest = '".$contest."'";
			$sql=mysqli_query($bd, $query);
			$nb = mysqli_affected_rows($bd);
			if ($nb > 0){
				$message->text = sprintf(_('Settings for %s contest saved !'), $contest);
				$message->type = 'success';
			}else{
				$message->text = sprintf(_('Error : I couldn\'t save the %s contest settings !'), $contest).'<br />'.mysqli_info($bd).mysqli_error($bd);
				$message->type = 'error';
			}
			break;
		case 'reset':
			$sql0=mysqli_query($bd, 'UPDATE images SET love = 0 WHERE contest = "'.$contest.'"');
			$sql1=mysqli_query($bd, 'DELETE FROM image_ip WHERE contest = "'.$contest.'"');
			if ($sql0 and $sql1){
				$message->text = sprintf(_('Votes for %s contest reinitialized !'), $contest);
				$message->type = 'success';
			}else{
				$message->text = sprintf(_('Error : I couldn\'t reinitialize votes for %s contest !'), $contest);
				$message->type = 'error';
			}
			$contest = null;
			break;
		case 'update':
			/** 
			* We update contest images.
			* Images that are in db but not in directory are removed from db, and images not in db yet are added. 
			*/
			$ok = true;
			$i_added = $i_deleted = 0;
			if ($handle = opendir($c_path.$contest)) {
		    while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
            if(in_array(substr($entry, strrpos($entry, ".")+1), $allowed_ext)){
							$images[str_replace('_', ' ',substr($entry, 0, strrpos($entry, ".")))] = $entry;
						}
					}
		    }
				closedir($handle);
		  }
			$query = 'SELECT * FROM `images` WHERE `contest` = "'.$contest.'" ORDER BY `img_name`';
			$sql_query=mysqli_query($bd, $query);
			while($row=mysqli_fetch_array($sql_query)){
				$img_id=$row['img_id'];
				$img_name=$row['img_name'];
				if (!array_key_exists($img_name, $images)){
					$sql = mysqli_query($bd, 'DELETE FROM images WHERE img_id = '.$img_id);
					$nb = mysqli_affected_rows($bd);
					if ($nb < 1){
						$ok = false;
						break;
					}else{
						$i_deleted++;
					}
				}else{
					unset($images[$img_name]);
				}
			}
			if ($ok){
				foreach ($images as $img_name => $img_url){
					$sql=mysqli_query($bd, 'INSERT INTO images (img_name, img_url, contest) VALUES ("'.$img_name.'", "'.$img_url.'", "'.$contest.'") ON DUPLICATE KEY UPDATE img_name = img_name');
					$nb = mysqli_affected_rows($bd);
					if ($nb < 1){
						$ok = false;
						break;
					}else{
						$i_added++;
					}
				}
			}
			
			if ($ok){
				$s_a = $s_d = '';
				if ($i_added ==0 or $i_added > 1){
					$s_a = 's';
				}
				if ($i_deleted ==0 or $i_deleted > 1){
					$s_d = 's';
				}
				$message->text = sprintf(_('%s contest photos updated !'), $contest).'<br /> <small>('.sprintf(ngettext('%s photo added', '%s photos added', $i_added), $i_added).' '.sprintf(ngettext('%s photo deleted', '%s photos deleted', $i_deleted), $i_deleted).')</small>';
				$message->type = 'success';
			}else{
				$message->text = sprintf(_('Error : I couldn\'t update photos for %s contest !'), $contest);
				$message->type = 'error';
			}
			$contest = null;	
			break;
		case 'add':
			/** We add contest in db. */
			$sql = mysqli_query($bd, 'DELETE FROM contests WHERE contest = "'.$contest.'"');
			$desc = null;
			$contest_name = $contest;
			$begin = date('Y/m/d');
			$end = date_create();
      $voting_type = "open";
			//date_add($end, date_interval_create_from_date_string('1 month'));
			date_modify($end, '+1 month');
			$end = date_format($end, 'Y/m/d');
      $contest_dir = opendir($c_path.$contest);
      /*while (false !== ($sub_entry = readdir($contest_dir))) {
        if ($sub_entry == 'description'){
					// If a file named 'description' is present, read it to get settings.
          $desc_file = file($c_path.$contest.'/'.$sub_entry, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
          foreach ($desc_file as $line) {
            if (stristr($line, 'name')){
              $linetab = explode(' : ', $line);
              $contest_name = $linetab[1];
            }elseif (stristr($line, 'description')){
              $linetab = explode(' : ', $line);
              $desc = $linetab[1];
            }elseif (stristr($line, 'begin date')){
              $linetab = explode(' : ', $line);
							$begin_raw = $linetab[1];
							if (date_valid($begin_raw)){
								$tab= explode("/", $begin_raw);
    						$begin = $tab[2] . '/'.$tab[1] . '/'.$tab[0] ;
							}
            }elseif (stristr($line, 'end date')){
              $linetab = explode(' : ', $line);
              $end_raw = $linetab[1];
							if (date_valid($end_raw)){
								$tab= explode("/", $begin_raw);
    						$end = $tab[2] . '/'.$tab[1] . '/'.$tab[0] ;
							}
            }
          }
				}
      }*/
			closedir($contest_dir);
			$sql = mysqli_query($bd, 'INSERT INTO contests (contest, contest_name, description, date_begin, date_end, voting_type) VALUES ("'.$contest.'", "'.$contest_name.'", "'.$desc.'", "'.$begin.'", "'.$end.'", "'.$voting_type.'")');
			if ($handle = opendir($c_path.$contest)) {
		    while (false !== ($entry = readdir($handle))) {
		      if ($entry != "." && $entry != "..") {
            if(in_array(substr($entry, strrpos($entry, ".")+1), $allowed_ext)){
							$img_name = str_replace('_', ' ',substr($entry, 0, strrpos($entry, ".")));
              $sql=mysqli_query($bd, 'INSERT INTO images (img_name, img_url, contest) VALUES ("'.$img_name.'", "'.$entry.'", "'.$contest.'")');
						}
					}
		    }
				closedir($handle);
		  }
			break;
		case 'del':
			/** We delete contest from db. With foreign keys magic, contest images are also removed from db. */
			$sql = mysqli_query($bd, 'DELETE FROM contests WHERE contest = "'.$contest.'"');
			$nb = mysqli_affected_rows($bd);
			if ($nb > 0){
				$message->text = sprintf(_('%s contest deleted !'), $contest);
				$message->type = 'success';
			}else{
				$message->text = sprintf(_('Error : I couldn\'t delete %s contest !'), $contest);
				$message->type = 'error';
			}
			break;
	}
}elseif ($tab == 'contests'){
	$contest = null;
}

if (isset($_POST['action']) and $_POST['action'] == 'settings_save'){
	
	/** Let's deal with settings saving ! */
	/** Settings are on one row. If this row is present, then we update it. If not, we create it. */
	if (!empty($settings)){
		$sql = 'UPDATE settings SET contests_name="'.htmlspecialchars($_POST['contests_name']).'", gallery_only = '.intval((isset($_POST['gallery_only']))?1:0).', contest_disp_title="'.htmlspecialchars($_POST['contest_disp_title']).'", display_other_contests='.intval((isset($_POST['display_other_contests']))?1:0).', max_length='.intval($_POST['max_length']).', language="'.htmlspecialchars($_POST['language']).'", date_format="'.htmlspecialchars($_POST['date_format']).'", default_contest="'.htmlspecialchars($_POST['default_contest']).'"';
	}else{
		$sql = 'INSERT INTO settings values ("'.htmlspecialchars($_POST['contests_name']).'", '.intval((isset($_POST['gallery_only']))?1:0).', "'.htmlspecialchars($_POST['contest_disp_title']).'", '.intval((isset($_POST['display_other_contests']))?1:0).', '.intval($_POST['max_length']).', "'.htmlspecialchars($_POST['language']).'", "'.htmlspecialchars($_POST['date_format']).'", "'.htmlspecialchars($_POST['default_contest']).'")';
	}
	$res = mysqli_query($bd, $sql);
	$error = mysqli_error($bd);
	/** Get number of rows affected. If equal to 0, it means that the settings has not been saved. */
	$nb = mysqli_affected_rows($bd);
	
	/** reloading settings now. */
	$sql=mysqli_query($bd, "SELECT * FROM settings");
	$settings = mysqli_fetch_object($sql);
	
	/** Language could have changed, let's reset it. */
	putenv("LC_ALL=".$settings->language);
	setlocale(LC_ALL, $settings->language);
	bindtextdomain("messages", "lang");
	bind_textdomain_codeset('messages', 'UTF-8');
	textdomain("messages");
	
	/** Notification message, processed after language reset. */
	if ($nb > 0){
		$message->text = _('Settings updated !');
		$message->type = 'success';
	}else{
		$message->text = _('Error : I was unable to update settings !').'<br />'.$error;
		$message->type = 'error';
	}
}

switch ($tab){
	case 'contests':
		contest_tab($c_path, $contest, $message);
		break;

}

function settings_tab($message = null){
	global $settings, $bd, $c_path;
	?>
	<form action="" class="small" method="post">
		<div class="input_group">
			<label><?php echo _('Contests Name'); ?> : </label>
			<input type="text" name="contests_name" id="contests_name" value="<?php echo (isset($settings->contests_name)) ? $settings->contests_name : _('Contest'); ?>" /> <?php echo info_disp(_('Contests name examples : \'Calendar\', \'Country\', \'Category\'. Defaut value : \'Contest\'')); ?>
		</div>
		<div class="input_group">
			<label><?php echo _('Gallery only'); ?> : </label>
			<?php
			if(isset($settings->gallery_only)){
				if ($settings->gallery_only){
					$checked = 'checked';
				}else{
					$checked = '';
				}
			}else{
				$checked = '';
			}
			?>
			<input type="checkbox" name="gallery_only" id="gallery_only" <?php echo $checked; ?> /> <?php echo info_disp(_('Disable voting system. This will transform Simple Photos Contest in a photos gallery.')); ?>
		</div>
		<div class="input_group">
			<label><?php echo _('Contest display title'); ?> </label>
			<input type="text" name="contest_disp_title" id="contest_disp_title" value="<?php echo (isset($settings->contest_disp_title)) ? $settings->contest_disp_title : _('Select your favorites photos for %s contest'); ?>" /> <?php echo info_disp(_('This is the short text displayed in header of a contest page. The %s variable is replaced by the contest name and must be present.')); ?>
		</div>
		<div class="input_group">
			<label><?php echo _('Display other contests'); ?> </label>
			<?php
			if(isset($settings->display_other_contests)){
				if ($settings->display_other_contests){
					$checked = 'checked';
				}else{
					$checked = '';
				}
			}else{
				$checked = '';
			}
			?>
			<input type="checkbox" name="display_other_contests" id="display_other_contests" <?php echo $checked; ?> /> <?php echo info_disp(_('Display a link to the other contests (if present).')); ?>
		</div>
		<div class="input_group">
			<label><?php echo _('Max thumbnail length'); ?> </label>
			<input type="text" name="max_length" id="max_length" value="<?php echo (isset($settings->max_length)) ? $settings->max_length : '600'; ?>" />px <?php echo info_disp(_('This value is the max width or height of thumbnails, depending of the biggest side of the photo.')); ?>
		</div>
		<div class="input_group">
			<label><?php echo _('Language'); ?> </label>
			<select name="language" id="language"> 
			<?php
			$languages = array();
			$languages[] = 'en_US.utf8';
			if ($handle = opendir('lang')) {
		    while (false !== ($entry = readdir($handle))) {
		      if ($entry != "." && $entry != "..") {
		        if (is_dir('lang/'.$entry)){
							$languages[] = $entry;
		        }
		      }
		    }
		    closedir($handle);
		  }
			sort($languages);
			foreach($languages as $language){
				?> <option <?php
				if ((isset($settings->language) and $language == $settings->language) or (!isset($settings->language) and $language == 'en_US.utf8')){
					?>selected<?php
				}
				?>><?php echo $language; ?></option><?php
			}
			?>
			</select> <?php echo info_disp(_('Select one of the available languages (based on languages files present in \'lang\' dir.')); ?>
		</div>
		<div class="input_group">
			<label><?php echo _('Date format'); ?> </label>
			<select name="date_format" id="date_format"> 
			<?php
			$formats = array('d/m/Y', 'm/d/Y', 'Y/m/d');

			foreach($formats as $format){
				?> <option <?php
				if ((isset($settings->date_format) and $format == $settings->date_format) or (!isset($settings->date_format) and $format == 'Y/m/d')){
					?>selected<?php
				}
				?>><?php echo $format; ?></option><?php
			}
			?>
			</select> <?php echo info_disp(_('The date format is the same as php date format. d = days, m = months, Y = year (4 digits).')); ?>
		</div>
		<div class="input_group">
			<label><?php echo _('Default contest'); ?> </label>
			<select name="default_contest" id="default_contest"> 
			<?php
			$contests = array();
			$sql=mysqli_query($bd, "SELECT contest FROM contests");
			while($row=mysqli_fetch_array($sql)){
				$contests[] = $row['contest'];
			}
			if (empty($contests)){
				?><option><em><?php echo _('No contests registered in db !'); ?></em></option><?php
			}else{
				foreach($contests as $contest){
					?> <option <?php
					if (isset($settings->default_contest) and $contest == $settings->default_contest){
						?>selected<?php
					}
					?>><?php echo $contest; ?></option><?php
				}
			}
			?>
			</select> <?php echo info_disp(_('Select one the registered contests in db to be the default contest displayed in frontend.')); ?>
		</div>
		<div class="form_buttons">
			<input type="submit" class="btn_primary" value="<?php echo _('Save'); ?>" id="save" name="save" /> 
			<input type="hidden" name="action" value="settings_save"/>
		</div>
	</form>
	<?php
}

function contest_tab($c_path, $contest = null, $message = null){
  global $bd;
	/** Let's populate $contests array. */
	$sql=mysqli_query($bd, "SELECT * FROM contests");
	while($row=mysqli_fetch_array($sql)){
		$contests[$row['contest']] = (object)array(	'contest_name'=> $row['contest_name'],
																								'description' => $row['description'],
																								'date_begin'	=> $row['date_begin'],
																								'date_end'		=> $row['date_end']
																							);
	}
	/** Prevent trying to display settings of deleted or non-existant contests. */
	if (!empty($contest) and !isset($contests[$contest])){
		$contest = null;
	}
	/** Display contests added in filesystem but not in db. */ 
	if (empty($contest)){
	  if ($handle = opendir($c_path)) {
	    while (false !== ($entry = readdir($handle))) {
	      if ($entry != "." && $entry != "..") {
	        if (is_dir($c_path.$entry)){
						if (!isset($contests[$entry])){
							$contests[$entry] = (object)array('not_added' => true);
						}
	        }
	      }
	    }
	    closedir($handle);
	  } 
	}
	if (empty($contest)){
		admin_header('Contests', $contest, $message);
		?>
		<div id="contest_table" class="table">
		<?php
		foreach ($contests as $cont_id => $cont){
			if (isset($cont->not_added)){
				/** contests who are not in db yet. */
				?>
				<ul class="item_wrap">
					<li class="item_actions"><a href="?tab=contests&contest=<?php echo $cont_id; ?>&action=add" title="<?php echo _('Add'); ?>"><span class="fa fa-plus win8Icon"title="<?php echo _('Add'); ?>"></span></a></li>
					<li class="item_title not_added"><?php echo $cont_id; ?></li>
					<li class="item_desc"></li>
					<li class="item_id">Id : <?php echo $cont_id; ?></li>
					<li class="item_dates"><?php echo _('This album has not been added to db yet.'); ?></li>
				</ul>
				<?php
			}else{
				/** Contests registered in db. */
				/** Is the contest closed ? */
				list($byear, $bmonth, $bday) = explode('-', $cont->date_begin);
				list($eyear, $emonth, $eday) = explode('-', $cont->date_end);
				if (time() >= mktime(0,0,0,$bmonth,$bday,$byear) and time() <= mktime(0,0,0,$emonth,$eday,$eyear)){
					$class = 'active';
				}else{
					$class = '';
				}
				?>
				<ul class="item_wrap <?php echo $class; ?>">
					<li class="item_actions">
						<a href="?tab=contests&contest=<?php echo $cont_id; ?>&action=stats" title="<?php echo _('Stats'); ?>"><span class="fa fa-bar-chart win8Icon" title="<?php echo _('Stats'); ?>"></span></a>&nbsp;
						<a href="?tab=contests&contest=<?php echo $cont_id; ?>&action=update" title="<?php echo _('Update'); ?>"><span class="fa fa-refresh win8Icon" title="<?php echo _('Update'); ?>"></span></a>&nbsp;
						<a href="?tab=contests&contest=<?php echo $cont_id; ?>" title="<?php echo _('Edit'); ?>"><span class="fa fa-pencil win8Icon" title="<?php echo _('Edit'); ?>"></span></a>&nbsp;
						<a href="?tab=contests&contest=<?php echo $cont_id; ?>&action=reset" title="<?php echo _('Reinitialize votes'); ?>"><span class="fa fa-undo win8Icon" title="<?php echo _('Reinitialize votes'); ?>"></span></a>&nbsp;
						<a href="?tab=contests&contest=<?php echo $cont_id; ?>&action=del" title="<?php echo _('Delete contest'); ?>"><span class="fa fa-trash win8Icon" title="<?php echo _('Delete contest'); ?>"></span></a>&nbsp;
					</li>
					<li class="item_title"><a title="<?php echo _('See contest'); ?>" href=".?contest=<?php echo $cont_id; ?>"><?php echo $cont->contest_name; ?></a></li>
					<li class="item_desc"><?php echo $cont->description; ?></li>
					<li class="item_id">Id : <?php echo $cont_id; ?></li>
					<li class="item_dates"><?php echo sprintf(_('Contest open to votes between %s and %s'), '<span class="date_begin">'.date_formatting($cont->date_begin).'</span>', '<span class="date_end">'.date_formatting($cont->date_end).'</span>'); ?></li>
				</ul>
				<?php
			}
		}
		?>
	</div>
	<?php 
	} elseif (isset($_GET['action']) and $_GET['action'] == 'stats'){
		/** Contest stats. */ 
		admin_header('Stats', $contest, $message);
		contest_stats($contest);
	} else {
		/** Contest settings. */ 
		$cont = $contests[$contest];
		admin_header('Contests', $contest, $message);
	?>
		<!--<h2><?php echo sprintf(_('%s contest'), $cont->contest_name); ?> : </h2>-->
		<form action="?tab=contests&contest=<?php echo $contest; ?>" method="post">
			<div class="input_group">
				<label><?php echo _('Name'); ?> : </label>
				<input type="text" name="contest_name" id="contest_name" value="<?php echo $cont->contest_name; ?>" />
			</div>
			<div class="input_group">
				<label><?php echo _('Description'); ?> : </label>
				<textarea name="description" id="description"><?php echo $cont->description; ?></textarea>
			</div>
			<label><?php echo _('Contest opening'); ?></label>
			<div class="input_group">
				<label for="date_begin"><?php echo _('From'); ?> </label>
				<input type="date" name="date_begin" id="date_begin" value="<?php echo date_formatting($cont->date_begin); ?>"/>
				<br />
				<label for="date_end"><?php echo _('To'); ?> </label>
				<input type="date" name="date_end" id="date_end" value="<?php echo date_formatting($cont->date_end); ?>"/>
			</div>
			<script>
				var beginDate = new Date("<?php echo date('D M d Y H:i:s O', strtotime($cont->date_begin)); ?>");
				var endDate = new Date("<?php echo date('D M d Y H:i:s O', strtotime($cont->date_end)); ?>");
			</script>
      <div class="input_group">
				<label><?php echo _('Voting'); ?> : </label>
				<select name="voting_type" id="voting_type" value="<?php echo $cont->voting_type; ?>" >
          <option value="contest"><?php echo _('Only one vote per contest'); ?></option>
          <option value="open"><?php echo _('Unlimited'); ?></option>
        </select>
			</div>
			<div class="form_buttons">
				<input type="submit" class="btn_primary" value="<?php echo _('Save'); ?>" id="save" name="save" /> 
				<input type="submit" value="<?php echo _('Delete'); ?>" id="del" name="del" />
			</div>
		</form>
	<?php 
	}
	admin_footer();
}

function admin_header($tab, $sub = null, $message = null){
	?>
<!DOCTYPE html>
<html lang="fr-FR">
  <head>
    <title><?php echo _('Admin panel'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/spc.css" type="text/css" />
		<link rel="icon" type="image/png" href="favicon.png" />
	</head>
	<body>
		<div id="content">
		<div id="header">
			<a href="<?php if (!empty($sub)){ echo 'admin'; } else { echo 'index'; } ?>.php<?php if (!empty($sub)){ echo '?tab='.strtolower($tab);} ?>" title="<?php echo _('Back'); ?>"><span class="fa fa-arrow-circle-o-left"></span><!--<img src="img/back.png" />--></a>
			<a href="admin.php"><?php echo _('Admin panel').' / '._($tab); ?></a>
			<?php
			if (!empty($sub)){
				?> / <span id="sub_title"><?php echo $sub;
			}
			?></span>
		</div>
		<?php echo disp_message($message); ?>
			<div id="admin_wrap">
				<div id="settings_bar">
					<a href="#" title="<?php echo _('General settings'); ?>" id="settings_disp"><span class="fa fa-sliders win8Icon" title="<?php echo _('General settings'); ?>"></span></a>
					<div id="settings_wrap">
						<h1><?php echo _('General settings'); ?></h1>
						<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
						<div class="viewport">
        			<div class="overview">
								<?php settings_tab($message); ?>
							</div>
						</div>
					</div>
				</div>
				<!--<ul id="tab_list">
					<li><a href="?tab=contests"><?php echo _('Contests'); ?></a></li>
					<li><a href="?tab=settings"><?php echo _('Settings'); ?></a></li>
				</ul>-->
	<?php
}

function admin_footer(){
	global $settings;
	?>
				</div>
				<div class="push"></div>
				</div>
				<div id="footer">
					<a href="https://github.com/Dric/simple-photos-contest"><span class="fa fa-github githubIcon"></span></a> <a href="about.php" class=""><span class="colored">S</span>imple <span class="colored">P</span>hotos <span class="colored">C</span>ontest</a> <span class="colored"><?php echo SPC_VERSION; ?></span> by <a href="http://www.driczone.net"><span class="colored">Dric</span></a>.
				</div>
			<script>
				var noTiling = true;
			</script>
			<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
			<script type="text/javascript" src="js/lightbox.min.js"></script>
			<script type="text/javascript" src="js/jqBarGraph.1.1.min.js"></script>
			<script type="text/javascript" src="js/jquery.tinyscrollbar.min.js"></script>
			<script type="text/javascript" src="js/contest.js"></script>
			<script type="text/javascript" src="js/admin.js"></script>
	</body>
</html>
	<?php
}