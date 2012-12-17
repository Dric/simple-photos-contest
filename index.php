<?php
include('config.php');
include('functions.php');
if ($settings){
	if (isset($_GET['contest']) and !empty($_GET['contest'])){
		$contest = htmlspecialchars($_GET['contest']);
	}else{
		$contest = $settings->default_contest;
	}
	$contests = array();
	$sql=mysql_query("SELECT * FROM contests");
	while($row=mysql_fetch_array($sql)){
		$contests[$row['contest']] = (object)array(	'contest_name'=> $row['contest_name'],
																								'description' => $row['description'],
																								'date_begin'	=> $row['date_begin'],
																								'date_end'		=> $row['date_end']
																							);
	}
}
?>
<!DOCTYPE html>
<html lang="<?php echo LANG; ?>">
  <head>
    <title><?php echo sprintf($settings->contests_name, $contest); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
		<link rel="icon" type="image/png" href="favicon.png" />
	</head>
	<body>
		<div id="settings-button">
			<?php
			if ($admin_logged){
				?>
			<a href="admin.php" title="<?php echo _('settings'); ?>"><img alt="<?php echo _('settings'); ?>" src="img/settings.png" /></a>
				<?php
			}else{
				?>
			<a href="#" title="<?php echo _('settings'); ?>" id="log_button"><img alt="<?php echo _('settings'); ?>" src="img/settings.png" /></a>
			<div id="login">
				<form class="small">
					<div class="input_group">
						<label><?php echo _('Password'); ?> : </label>
						<input type="password" id="login_auth"/> <img id="log_send" alt="<?php echo _('Login'); ?>" src="img/go.png" />
					</div>
				</form> 
			</div>
				<?php
			}
			?>
		</div>
		<?php 
		/** If $settings is false, then there is no settings. Let's display a message ! */
		if (!$settings){
			$message = (object)array('type' => 'error', 'text' => _('It seems that you just installed Simple Photos Contest. Please click on the settings button and log in to configure this site.'));
			echo disp_message($message);
		}elseif (empty($settings->default_contest)){
			$message = (object)array('type' => 'error', 'text' => _('There is no default contest defined. Please click on the settings button and log in to set one.'));
			echo disp_message($message);
		}else{
			$max_value = $settings->max_length;
		?>
		<div id="header"><?php echo sprintf($settings->contest_disp_title, '<span class="header-contest">'.$contest.'</span>'); ?></div>
		<div id="contests_list">
			<?php
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
			<div id="wrap">
				<?php
					$sql=mysql_query("SELECT * FROM images WHERE contest = ".$contest." ORDER BY img_name");
					while($row=mysql_fetch_array($sql)){
						$img_id=$row['img_id'];
						$img_name=$row['img_name'];
						$img_url= $c_path.$contest.'/'.$row['img_url']; 
						$love=$row['love'];
						list($width, $height) = getimagesize($img_url);
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
				<div class="img-container" <?php echo $attr; ?>>
					<div class="box" align="left">
						<?php if (!$settings->gallery_only){ ?>
						<a href="#" class="love" id="<?php echo $img_id; ?>" data-contest="<?php echo $contest; ?>">
							<span title="<?php echo _('I\'m in love !'); ?>" class="on_img" align="left"> <?php echo $love; ?> </span> 
						</a>
						<?php }else{ ?>
						&nbsp;
						<?php } ?> 
						<div class="pull-right"><?php echo $img_name; ?></div>
					</div>
					<a href="<?php echo $img_url; ?>" title="<?php echo $img_name; ?>" rel="lightbox"><img alt="<?php echo $img_name; ?>" class="img" src="timthumb.php?src=<?php echo $img_url.$param; //virer l'url calendrier ?>" /></a>
				</div>
				<?php
					}
				?>
			</div>
		</div>
		<?php } ?>
		<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
		<script type="text/javascript" src="js/slimbox2.js"></script>
		<script type="text/javascript" src="js/jquery.freetile.min.js"></script>
		<script type="text/javascript" src="js/contest.js"></script>
	</body>
</html>
