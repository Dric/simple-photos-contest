<?php
if (!file_exists('config.php')){
	header('Location:install/install.php');
}
include('config.php');
include('functions.php');
if ($settings){
	if (isset($_GET['contest']) and !empty($_GET['contest'])){
		$contest = htmlspecialchars($_GET['contest']);
	}else{
		$contest = $settings->default_contest;
	}
	$contests = array();
	$sql=mysqli_query($bd, "SELECT * FROM contests");
	while($row=mysqli_fetch_array($sql)){
		$contests[$row['contest']] = (object)array(	'contest_name'=> $row['contest_name'],
																								'description' => $row['description'],
																								'date_begin'	=> $row['date_begin'],
																								'date_end'		=> $row['date_end']
																							);
	}
}
?>
<!DOCTYPE html>
<html lang="<?php echo $settings->language; ?>">
  <head>
    <title><?php echo sprintf($settings->contests_name, $contest); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/spc.css" type="text/css" />
		<link rel="icon" type="image/png" href="favicon.png" />
	  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	  <!--[if lt IE 9]>
	  <script src="js/html5shiv.js"></script>
	  <script src="js/respond.min.js"></script>
	  <![endif]-->
	</head>
	<body>
		<div id="settings-button">
			<?php
			if ($admin_logged){
				?>
			<a href="admin.php" title="<?php echo _('settings'); ?>"><span class="fa fa-cog" title="<?php echo _('settings'); ?>"></span></a>
				<?php
			}else{
				?>
			<a href="#" title="<?php echo _('Settings'); ?>" id="log_button"><span class="fa fa-cog" title="<?php echo _('settings'); ?>"></span></a>
			<div id="login">
				<form class="small">
					<div class="input_group">
						<label><?php echo _('Password'); ?> : </label>
						<input type="password" id="login_auth"/> <span id="log_send" class="fa fa-sign-in fa-2x" title="<?php echo _('Login'); ?>"></span>
					</div>
				</form> 
			</div>
				<?php
			}
			?>
		</div>
		<?php 
		if (!$settings) {
			/** If $settings is false, then there is no settings. Let's display a message ! */
			$message = (object)array('type' => 'error', 'text' => _('It seems that you just installed Simple Photos Contest. Please click on the settings button and log in to configure this site.'));
			echo disp_message($message);
		}elseif (empty($settings->default_contest)and count($contests) < 1){
			/** There are settings, but default contest is not defined. Let's display a message too ! */
			$message = (object)array('type' => 'error', 'text' => _('There is no default contest defined. Please click on the settings button and log in to set one.'));
			echo disp_message($message);
		}else{
			/** The first contest will be the default contest if none is set */
			if (is_null($contest)){
				reset($contests);
				$contest = key($contests);
			}

			/** That's all good, let's display the page. */
			$max_value = $settings->max_length;
			/** Get dates variables. */
			list($byear, $bmonth, $bday) = explode('-', $contests[$contest]->date_begin);
			list($eyear, $emonth, $eday) = explode('-', $contests[$contest]->date_end);
			/** @var bool $activeContest Is the contest active or not ? */
			$activeContest = (time() >= mktime(0,0,0,$bmonth,$bday,$byear) and time() <= mktime(0,0,0,$emonth,$eday,$eyear)) ? true : false;
		?>
		<div id="header"><?php echo sprintf($settings->contest_disp_title, '<span class="header-contest">'.$contest.'</span>'); ?></div>
		<div id="contests_list">
			<?php
			/** If allowed and if other contests exist, display a link to them. */
			if (count($contests) > 1 and $settings->display_other_contests){
				echo _('Other contests').' : ';
				foreach ($contests as $cont => $cont_item){
					if ($cont != $contest){
						if (!empty($cont_item->contest_name)){
							$cont_disp = $cont_item->contest_name;
						}else{
							$cont_disp = $cont;
						}
						?><span class="contests"><a title="<?php echo $cont_item->description; ?>" href="?contest=<?php echo $cont; ?>"><?php echo $cont_disp; ?></a></span> <?php
					}
				}
			}
			?>
		</div>
		<div align="center">
			<div class="contestStatus">
				<?php
				if (!$settings->gallery_only) {
					if ($activeContest) {
						?><h2><?php echo _('Voting is open for this contest.'); ?></h2><?php
					} else {
						?><h2><?php echo _('Voting is closed for this contest.'); ?></h2><?php
					}
				}
				?>
			</div>
			<section id="wrap" role="main">
				<?php
					/** Query images from db. */
					$sql=mysqli_query($bd, "SELECT * FROM images WHERE contest = '".$contest."' ORDER BY img_name");
					while($row=mysqli_fetch_array($sql)){
						$img_id=$row['img_id'];
						$img_name=$row['img_name'];
						$img_url= $c_path.$contest.'/'.$row['img_url'];
            $thumb_url = 'cache/'.$contest.'/'.$row['img_url'];
						$love=$row['love'];
            if (file_exists($thumb_url)) {
              list($width, $height) = getimagesize($thumb_url);
            }
						// If Image thumbnail doesn't exists or if the max_value has changed, we (re)create the thumbnail
            if (!file_exists($thumb_url) or ($width != $max_value and $height != $max_value)){
              include_once('SimpleImage.php');
              $img_thumb = new SimpleImage($img_url);
              $img_thumb->best_fit($max_value, $max_value);
	            // If contest thumbnail folder doesn't exists, we create it
              if (!file_exists('cache/'.$contest)) mkdir('cache/'.$contest);
              $img_thumb->save($thumb_url);
              list($width, $height) = getimagesize($thumb_url);
            }
						$attr = '';
						if ($width>$height){
							if ($height > $max_value){
								$param = '&h='.$max_value;
								$attr = 'style="width: '.($width/$height) * $max_value .'px; height: '.$max_value.'px;"';
							}
						}else{
							if ($width > $max_value){
								$param = '&w='.$max_value;
								$attr = 'style="width: '.$max_value.'px; height: '.($height/$width) * $max_value .'px;"';
							}
						}
				?>
				<article class="img-container" <?php echo $attr; ?>>
					<div  id="box-<?php echo $img_id; ?>" class="caption">
					<?php
						/** If allowed and if present date is within the contest date range, display the vote icon. */
						if (!$settings->gallery_only and $activeContest){ ?>
						<div href="#" class="love" id="<?php echo $img_id; ?>" data-contest="<?php echo $contest; ?>">
							<span title="<?php echo _('I\'m in love !'); ?>"><span class="fa fa-heart"></span> <?php echo $love; ?> </span>
						</div>
						<?php }else{ ?>
						&nbsp;
						<?php }	?>
						<div class="photo-title"><?php echo (strlen($img_name) > (int)round($width/12)) ? substr($img_name, 0, round($width/11)).'&hellip;':$img_name; ?></div>
					</div>
					<a href="<?php echo $img_url; ?>" title="<?php echo $img_name; ?>" data-lightbox="<?php echo $img_id; ?>" data-title="<?php echo $img_name; ?>" class=""><img alt="<?php echo $img_name; ?>" class="img" src="<?php echo $thumb_url; ?>" /></a>
				</article>
				<?php
					}
				?>
			</section>
		</div>
		<?php } ?>
		<script>
			var noTiling = false;
		</script>
		<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
		<script type="text/javascript" src="js/lightbox.min.js"></script>
		<script type="text/javascript" src="js/freetile0.3.1.js"></script>
		<script type="text/javascript" src="js/noDuplicate.js"></script>
		<script type="text/javascript" src="js/contest.js"></script>
	</body>
</html>
