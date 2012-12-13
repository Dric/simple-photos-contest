<?php
include('config.php');
include('functions.php');

if (!$admin_logged){
	die('Nice tried, but your are not logged in.');
}
/** List of db recorded contests. */
$contests = array();
/** Notification message. */
$message = new stdClass;
if (isset($_GET['contest']) and !empty($_GET['contest'])){
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
			if ($desc == ''){
				$desc = 'NULL';
			}else{
				$desc = "'".$desc."'";
			}
			$query = "UPDATE contests SET contest_name = '".$contest_name."', description = ".$desc.", date_begin = '".$date_begin."', date_end = '".$date_end."' WHERE contests.contest = '".$contest."'";
			$sql=mysql_query($query);
			$nb = mysql_affected_rows();
			if ($nb > 0){
				$message->text = sprintf(_('Settings for %s contest saved !'), $contest);
				$message->type = 'success';
			}else{
				$message->text = sprintf(_('Error : I couldn\'t save the %s contest settings !'), $contest).'<br />'.mysql_info();
				$message->type = 'error';
			}
			break;
		case 'reset':
			$sql0=mysql_query('UPDATE images SET love = 0 WHERE contest = "'.$contest.'"');
			$sql1=mysql_query('DELETE FROM image_ip WHERE contest = "'.$contest.'"');
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
			$sql=mysql_query("SELECT * FROM images WHERE contest = ".$contest." ORDER BY img_name");
			while($row=mysql_fetch_array($sql)){
				$img_id=$row['img_id'];
				$img_name=$row['img_name'];
				if (!array_key_exists($img_name, $images)){
					$sql = mysql_query('DELETE FROM images WHERE img_id = '.$img_id);
					$nb = mysql_affected_rows();
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
					$sql=mysql_query('INSERT INTO images (img_name, img_url, contest) VALUES ("'.$img_name.'", "'.$img_url.'", "'.$contest.'")');
					$nb = mysql_affected_rows();
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
			$sql = mysql_query('DELETE FROM contests WHERE contest = "'.$contest.'"');
			$desc = null;
			$contest_name = $contest;
			$begin = date('Y/m/d');
			$end = date_create();
			date_add($end, date_interval_create_from_date_string('1 month'));
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
			$sql = mysql_query('INSERT INTO contests (contest, contest_name, description, date_begin, date_end) VALUES ("'.$contest.'", "'.$contest_name.'", "'.$desc.'", "'.$begin.'", "'.$end.'")');
			if ($handle = opendir($c_path.$contest)) {
		    while (false !== ($entry = readdir($handle))) {
		      if ($entry != "." && $entry != "..") {
            if(in_array(substr($entry, strrpos($entry, ".")+1), $allowed_ext)){
							$img_name = str_replace('_', ' ',substr($entry, 0, strrpos($entry, ".")));
              $sql=mysql_query('INSERT INTO images (img_name, img_url, contest) VALUES ("'.$img_name.'", "'.$entry.'", "'.$contest.'")');
						}
					}
		    }
				closedir($handle);
		  }
			break;
		case 'del':
			/** We delete contest from db. With foreign keys magic, contest images are also removed from db. */
			$sql = mysql_query('DELETE FROM contests WHERE contest = "'.$contest.'"');
			$nb = mysql_affected_rows();
			if ($nb > 0){
				$message->text = sprintf(_('%s contest deleted !'), $contest);
				$message->type = 'success';
			}else{
				$message->text = sprintf(_('Error : I couldn\'t delete %s contest !'), $contest);
				$message->type = 'error';
			}
			break;
	}
}else{
	$contest = null;
}
/** Let's populate $contests array. */
$sql=mysql_query("SELECT * FROM contests");
while($row=mysql_fetch_array($sql)){
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
?>
<!DOCTYPE html>
<html lang="fr-FR">
  <head>
    <title><?php echo _('Admin panel'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
		<link rel="icon" type="image/png" href="favicon.png" />
	</head>
	<body>
		<div id="header">
			<a href="<?php if (!empty($contest)){ echo 'admin'; } else { echo 'love'; } ?>.php" title="<?php echo _('Back'); ?>"><img src="img/back.png" /></a> 
			<?php echo _('Admin panel'); ?>
			<?php
			if (!empty($contest)){
				?> / <span id="contest_title"><?php echo $contests[$contest]->contest_name;
			}
			?></span>
		</div>
		<?php echo disp_message($message); ?>
			<div id="admin_wrap">
				<?php
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
				
				?>
				<h2><?php echo _('Contest'); ?> :</h2>
				<div id="contest_table" class="table">
				<?php
					foreach ($contests as $cont_id => $cont){
						if (isset($cont->not_added)){
							/** contests who are not in db yet. */
							?>
							<ul class="item_wrap">
								<li class="item_actions"><a href="?contest=<?php echo $cont_id; ?>&action=add" title="<?php echo _('Add'); ?>"><img src="img/add.png" alt="<?php echo _('Add'); ?>"/></a></li>
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
									<a href="?contest=<?php echo $cont_id; ?>&action=update" title="<?php echo _('Update'); ?>"><img src="img/refresh.png" alt="<?php echo _('Update'); ?>"/></a>&nbsp;
									<a href="?contest=<?php echo $cont_id; ?>" title="<?php echo _('Edit'); ?>"><img src="img/edit.png" alt="<?php echo _('Edit'); ?>"/></a>
									<a href="?contest=<?php echo $cont_id; ?>&action=reset" title="<?php echo _('Reinitialize votes'); ?>"><img src="img/reset.png" alt="<?php echo _('Reinitialize votes'); ?>"/></a>&nbsp;
									<a href="?contest=<?php echo $cont_id; ?>&action=del" title="<?php echo _('Delete contest'); ?>"><img src="img/del.png" alt="<?php echo _('Delete contest'); ?>"/></a>&nbsp;
								</li>
								<li class="item_title"><?php echo $cont->contest_name; ?></li>
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
					} else {
						/** Contest settings. */ 
						$cont = $contests[$contest];
				?>
					<h2><?php echo sprintf(_('%s contest'), $cont->contest_name); ?> : </h2>
					<form action="?contest=<?php echo $contest; ?>" method="post">
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
							<input type="text" name="date_begin" id="date_begin" value="<?php echo date_formatting($cont->date_begin); ?>" />
							<br />
							<label for="date_end"><?php echo _('To'); ?> </label>
							<input type="text" name="date_end" id="date_end" value="<?php echo date_formatting($cont->date_end); ?>" />
						</div>
						<div class="form_buttons">
							<input type="submit" class="btn_primary" value="<?php echo _('Save'); ?>" id="save" name="save" /> 
							<input type="submit" value="<?php echo _('Delete'); ?>" id="del" name="del" />
						</div>
					</form>
				<?php } ?>
			</div>
			<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
			<script type="text/javascript" src="js/slimbox2.js"></script>
			<script type="text/javascript" src="js/zebra_datepicker.js"></script>
			<script type="text/javascript" src="js/contest.js"></script>
			<script type="text/javascript" src="js/admin.js"></script>
	</body>
</html>