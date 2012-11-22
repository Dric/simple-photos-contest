<?php
include('config.php');
include('functions.php');
if (isset($_GET['annee']) and !empty($_GET['annee'])){
	$contest = htmlspecialchars($_GET['annee']);
}else{
	$contest = $default_contest;
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
?>
<!DOCTYPE html>
<html lang="fr-FR">
  <head>
    <title>Sélection pour le calendrier <?php echo $contest; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
		<link rel="icon" type="image/png" href="favicon.png" />
	</head>
	<body>
		<div id="settings-button"><a href="admin.php" title="Paramètres"><img alt="Paramètres" src="img/settings.png" /></a></div>
		<div id="header">Sélectionnez les madames du calendrier <span class="header-contest"><?php echo $contest; ?></span></div>
		<div id="contests_list">
			<?php
			if (count($contests) > 1){
				?>Autres années : <?php
				foreach ($contests as $cont => $cont_item){
					if ($cont != $contest){
						if (!empty($cont_item->contest_name)){
							$cont_disp = $cont_item->contest_name;
						}else{
							$cont_disp = $cont;
						}
						?><span class="contests"><a title="<?php echo $cont_item->description; ?>" href="?annee=<?php echo $cont; ?>"><?php echo $cont_disp; ?></a></span> <?php
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
						<a href="#" class="love" id="<?php echo $img_id; ?>" data-contest="<?php echo $contest; ?>">
							<span title="Je suis amoureux !" class="on_img" align="left"> <?php echo $love; ?> </span> 
						</a> 
						<div class="pull-right"><?php echo $img_name; ?></div>
					</div>
					<a href="<?php echo $img_url; ?>" title="<?php echo $img_name; ?>" rel="lightbox"><img alt="<?php echo $img_name; ?>" class="img" src="timthumb.php?src=<?php echo 'calendrier/'.$img_url.$param; //virer l'url calendrier ?>" /></a>
				</div>
				<?php
					}
				?>
			</div>
		</div>
		<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
		<script type="text/javascript" src="js/slimbox2.js"></script>
		<script type="text/javascript" src="js/jquery.freetile.min.js"></script>
		<script type="text/javascript" src="js/contest.js"></script>
	</body>
</html>
