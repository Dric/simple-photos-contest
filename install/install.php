<?php
session_start();
/** Install script for Simple Photos Contests */

if (isset($_POST['step'])){
	if (isset($_POST['back'])){
		$step = intval($_POST['step']) - 2;
	}else{
		$step = intval($_POST['step']);
	}
}elseif(isset($_GET['step'])){
	$step = intval($_GET['step']);
}else{
	$step = 0;
}
$result = new stdClass;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Simple Photos Contest - Installation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="./../style.css" type="text/css" media="screen" />
		<link rel="icon" type="image/png" href="./../favicon.png" />
		<style type="text/css">
			body{
				font-size: 16px;
			}
			h1, h2{
				color: #ff0056;
				margin: 6px;
			}
			h1{
				font-size: 60px;
			}
			dt{
				color: #999;
			}
			dd, dl{
				margin-left: 10px;
			}
			p{
				margin-left: 10px;
				margin-bottom: 10px;
			}
			.code{
				border: 1px solid #ff0056;
				padding: 6px;
			}
		</style>
	</head>
	<body>
		<div id="wrap">
			<?php
			switch ($step){
				case 0:
					?>
					<h1>Welcome to Simple Photos Contest Installer</h1>
					<form class="large" method="POST" action="install.php">
						<p><em>This installer will be displayed in english only</em>.</p>
						<p>Simple Photos Contests is a lightweight gallery photos with a voting system, written in PHP and JS.</p>
						<p>For more information about installing and setting up SPC, please follow this link : <a href="https://github.com/Dric/simple-photos-contest">Simple Photos Contest Github page</a></p>
						<p>Click on the 'next' button to continue :</p>
						<div class="form_buttons">
							<input type="submit" value="Next" name="submit"/>
							<input type="hidden" name="step" value="1"/>
						</div>
					</form>
					<?php
					break;
				case 1:
					?>
					<h1>Install : Database settings</h1>
					<form class="large" method="POST" action="install.php">
						<p>SPC needs a MySQL database (with InnoDB engine). Please create one and complete the fields above :</p>
						<?php
						if (isset($_SESSION['message'])){
							echo $_SESSION['message'];
							unset ($_SESSION['message']);
						}
						?>
						<div class="input_group">
							<label>Database name : </label>
							<input type="text" name="db_name" id="db_name" value="<?php echo (isset($_SESSION['db_name'])) ? $_SESSION['db_name'] : ''; ?>" />
						</div>
						<div class="input_group">
							<label>Database prefix : </label>
							<input type="text" name="db_prefix" id="db_prefix" value="<?php echo (isset($_SESSION['db_prefix'])) ? $_SESSION['db_prefix'] : ''; ?>" /> <img alt="Prefix can be useful if you have only one database to share between two or more applications, as it prevent two applications who each have tables with identical name from interfering. Let it empty if you don't need prefix." src="./../img/info.png" />
						</div>
						<div class="input_group">
							<label>Database host : </label>
							<input type="text" name="db_host" id="db_host" value="<?php echo (isset($_SESSION['db_host'])) ? $_SESSION['db_host'] : 'localhost'; ?>" />
						</div>
						<div class="input_group">
							<label>Database user : </label>
							<input type="text" name="db_user" id="db_user" value="<?php echo (isset($_SESSION['db_user'])) ? $_SESSION['db_user'] : ''; ?>" />
						</div>
						<div class="input_group">
							<label>Database user password : </label>
							<input type="password" name="db_pwd" id="db_pwd" value="<?php echo (isset($_SESSION['db_pwd'])) ? $_SESSION['db_pwd'] : ''; ?>" /> <img alt="User password is mandatory, even if MySQL doesn't require it." src="./../img/info.png" />
						</div>
						<div class="form_buttons">
							<input type="submit" value="Back" name="back"/>
							<input type="submit" value="Next" name="submit"/>
							<input type="hidden" name="step" value="2"/>
						</div>
					</form>
					<?php
					break;
				case 2:
					$result->ok = true;
					$result->message = '';
					if (isset($_POST['submit'])){
						if (isset($_POST['db_name']) and !empty($_POST['db_name'])){
							$_SESSION['db_name'] = htmlspecialchars($_POST['db_name']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Database name is empty !</div>';
						}
						if (isset($_POST['db_prefix'])){
							if (!empty($_POST['db_prefix'])){
								$_SESSION['db_prefix'] = htmlspecialchars($_POST['db_prefix']);
							}else{
								$_SESSION['db_prefix'] = null;
							}
						}
						if (isset($_POST['db_host']) and !empty($_POST['db_host'])){
							$_SESSION['db_host'] = htmlspecialchars($_POST['db_host']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Database host is empty !</div>';
						}
						if (isset($_POST['db_user']) and !empty($_POST['db_user'])){
							$_SESSION['db_user'] = htmlspecialchars($_POST['db_user']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Database user is empty !</div>';
						}
						if (isset($_POST['db_pwd']) and !empty($_POST['db_pwd'])){
							$_SESSION['db_pwd'] = htmlspecialchars($_POST['db_pwd']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Database user password is empty !</div>';
						}
						if ($result->ok){
							$bd = mysql_connect($_SESSION['db_host'], $_SESSION['db_user'], $_SESSION['db_pwd']);
							if (!$bd){
								$result->ok = false;
								$result->message .= '<div class="alert error">Can\'t connect to MySQL Server !</div>';
							}else{
								$db_name = (isset($_SESSION['db_prefix'])) ? $_SESSION['db_prefix'].'_'.$_SESSION['db_name'] : $_SESSION['db_name'];
								$res = mysql_select_db($db_name, $bd);
								if (!$res){
									$result->ok = false;
									$result->message .= '<div class="alert error">Can\'t select database !</div>';
								}
							}
						}
					}
					if (!$result->ok){
						$_SESSION['message'] = $result->message;
						header('Location: ?step=1');
					}else{
						//$_SESSION['message'] = '<div class="alert success">Db connection successful !</div>';
						?>
						<h1>Install : Administration password</h1>
						<form class="large" method="POST" action="install.php">
							<p>SPC needs a password to access the admin panel. the password will be stored in the same file as your database settings.</p>
						<?php
						if (isset($_SESSION['message'])){
							echo $_SESSION['message'];
							unset ($_SESSION['message']);
						}
						?>
							<div class="input_group">
								<label>Admin password : </label>
								<input type="password" name="admin_pwd" id="admin_pwd" value="<?php echo (isset($_SESSION['admin_pwd'])) ? $_SESSION['admin_pwd'] : ''; ?>" /> <img alt="Using a unique password (not used on other websites) is recommended, as if this password is hacked, it won't be usable elsewhere." src="./../img/info.png" />
								<div id="result" class="pagination-centered"><div id="pwd-str" class="btn-danger">Your password can be cracked in <span id="time">less than a second</span>. <img alt="Based on 2 800 000 tries per second, with a basic brute force attack. Other attack types can be shorter (dictionary, regex, etc.)" src="./../img/info.png" /></div></div>
							</div>
							<div class="form_buttons">
								<input type="submit" value="Back" name="back"/>
								<input type="submit" value="Next" name="submit"/>
								<input type="hidden" name="step" value="3"/>
							</div>
						</form>
						<?php
					}
					break;
				case 3:
					$result->ok = true;
					$result->message = '';
					if (isset($_POST['submit'])){
						if (isset($_POST['admin_pwd']) and !empty($_POST['admin_pwd'])){
							$_SESSION['admin_pwd'] = htmlspecialchars($_POST['admin_pwd']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Admin password is empty !</div>';
						}
					}
					if (!$result->ok){
						$_SESSION['message'] = $result->message;
						header('Location: ?step=2');
					}else{
						?>
						<h1>Install : Other configuration settings</h1>
						<form class="large" method="POST" action="install.php">
							<p>Here are a few configuration settings that can be modified (for advanced users). If you don't know what to set here, leave the default values.</p>
						<?php
						if (isset($_SESSION['message'])){
							echo $_SESSION['message'];
							unset ($_SESSION['message']);
						}
						?>
							<div class="input_group">
								<label>Photos directory : </label>
								<input type="text" name="photos_dir" id="photos_dir" value="<?php echo (isset($_SESSION['photos_dir'])) ? $_SESSION['photos_dir'] : 'photos/'; ?>" /> <img alt="You can modify the directory that holds the contests. Default value : 'photos/'" src="./../img/info.png" />
							</div>
							<div class="input_group">
								<label>Cookie name : </label>
								<input type="text" name="cookie_name" id="cookie_name" value="<?php echo (isset($_SESSION['cookie_name'])) ? $_SESSION['cookie_name'] : 'spc'; ?>" /> <img alt="You can modify the name of the cookie used for administration. Default value : 'spc'" src="./../img/info.png" />
							</div>
							<div class="form_buttons">
								<input type="submit" value="Back" name="back"/>
								<input type="submit" value="Next" name="submit"/>
								<input type="hidden" name="step" value="4"/>
							</div>
						</form>
						<?php
					}
					break;
				case 4:
					$result->ok = true;
					$result->message = '';
					if (isset($_POST['submit'])){
						if (isset($_POST['photos_dir']) and !empty($_POST['photos_dir'])){
							$_SESSION['photos_dir'] = htmlspecialchars($_POST['photos_dir']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Photos dir is empty !</div>';
						}
						if (isset($_POST['cookie_name']) and !empty($_POST['cookie_name'])){
							$_SESSION['cookie_name'] = htmlspecialchars($_POST['cookie_name']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Cookie name is empty !</div>';
						}
					}
					if (!$result->ok){
						$_SESSION['message'] = $result->message;
						header('Location: ?step=3');
					}else{
						/** Connection to DB. */
						$bd = mysql_connect($_SESSION['db_host'], $_SESSION['db_user'], $_SESSION['db_pwd']);
						if (!$bd){
							$result->ok = false;
							$result->message .= '<div class="alert error">Can\'t connect to MySQL Server !</div>';
						}else{
							$db_name = (isset($_SESSION['db_prefix'])) ? $_SESSION['db_prefix'].'_'.$_SESSION['db_name'] : $_SESSION['db_name'];
							$res = mysql_select_db($db_name, $bd);
							if (!$res){
								$result->ok = false;
								$result->message .= '<div class="alert error">Can\'t select database !</div>';
							}
						}
						/** Import sql install file (http://shinephp.com/php-code-to-execute-mysql-script/) */
						
						@trigger_error("");
						$f = @fopen('install.sql',"r");
						if (!$f){
							$error = error_get_last();
							$err_tab = explode(': ', $error['message'], 2);
							$_SESSION['message'] = '<div class="alert error">'.$err_tab[1].'</div>';
							$result->ok = false;
						}
						$sqlFile = fread($f, filesize('install.sql'));
						$sqlArray = explode(';',$sqlFile);
						foreach ($sqlArray as $stmt) {
						  if (strlen($stmt)>3) {
						    $res = mysql_query($stmt);
						    if (!$res) {
						      $sqlErrorCode = mysql_errno();
						      $sqlErrorText = mysql_error();
						      $sqlStmt = $stmt;
									$result->ok = false;
									$result->message .= '<div class="alert error">An error occured during installation!<br/>Error code: '.$sqlErrorCode.'<br/>Error text: '.$sqlErrorText.'<br/>Statement:<br/>'.$sqlStmt.'</div>';
						      break;
						    }
						  }
						}
						if ($result->ok) {
						  $result->message =  '<div class="alert success">Database has been succesfully created !</div>';
						}
						fclose($f);
						$_SESSION['message'] = $result->message;
					?>
					<h1>Install : Database creation</h1>
					<form class="large" method="POST" action="install.php">
						<p>Installer has tried to create the database structure with the install.sql script.</p>
						<?php
						if (isset($_SESSION['message'])){
							echo $_SESSION['message'];
							unset ($_SESSION['message']);
						}
						?>
						<div class="form_buttons">
							<input type="submit" value="Back" name="back"/>
							<?php if ($result->ok){ ?>
							<input type="submit" value="Next" name="submit"/>
							<?php
							}
							?>
							<input type="hidden" name="step" value="5"/>
						</div>
					</form>
					<?php
					}
					break;
				case 5:
					$result->ok = true;
					$result->message = '';
					if (isset($_POST['submit'])){
						//Nothing to put here yet, but I leave this in case of change.
					}
					if (!$result->ok){
						$_SESSION['message'] = $result->message;
						header('Location: ?step=4');
					}else{
					?>
					<h1>Install : General settings</h1>
					<form class="large" method="POST" action="install.php">
						<p>The settings defined here are available in admin panel of SPC.</p>
						<?php
						if (isset($_SESSION['message'])){
							echo $_SESSION['message'];
							unset ($_SESSION['message']);
						}
						?>
						<div class="input_group">
							<label>Contests Name : </label>
							<input type="text" name="s_contests_name" id="s_contests_name" value="<?php echo (isset($_SESSION['s_contests_name'])) ? $_SESSION['s_contests_name'] : 'Contest'; ?>" /> <img alt="Contests name examples : 'Calendar', 'Country', 'Category'. Defaut value : 'Contest'" src="./../img/info.png" />
						</div>
						<div class="input_group">
							<label>Gallery only : </label>
							<?php
							if(isset($_SESSION['s_gallery_only'])){
								if ($_SESSION['s_gallery_only']){
									$checked = 'checked';
								}else{
									$checked = '';
								}
							}else{
								$checked = '';
							}
							?>
							<input type="checkbox" name="s_gallery_only" id="s_gallery_only" <?php echo $checked; ?> /> <img alt="Disable voting system. This will transform Simple Photos Contest in a photos gallery." src="./../img/info.png" />
						</div>
						<div class="input_group">
							<label>Contest display title</label>
							<input type="text" name="s_contest_disp_title" id="s_contest_disp_title" value="<?php echo (isset($_SESSION['s_contest_disp_title'])) ? $_SESSION['s_contest_disp_title'] : 'Select your favorites photos for %s contest'; ?>" /> <img alt="This is the short text displayed in header of a contest page. The %s variable is replaced by the contest name and must be present." src="./../img/info.png" />
						</div>
						<div class="input_group">
							<label>Display other contests</label>
							<?php
							if(isset($_SESSION['s_display_other_contests'])){
								if ($_SESSION['s_display_other_contests']){
									$checked = 'checked';
								}else{
									$checked = '';
								}
							}else{
								$checked = 'checked';
							}
							?>
							<input type="checkbox" name="s_display_other_contests" id="s_display_other_contests" <?php echo $checked; ?> /> <img alt="Display a link to the other contests (if present)." src="./../img/info.png" />
						</div>
						<div class="input_group">
							<label>Max thumbnail length</label>
							<input type="text" name="s_max_length" id="s_max_length" value="<?php echo (isset($_SESSION['s_max_length'])) ? $_SESSION['s_max_length'] : '400'; ?>" />px <img alt="This value is the max width or height of thumbnails, depending of the biggest side of the photo." src="./../img/info.png" />
						</div>
						<div class="input_group">
							<label>Language</label>
							<select name="s_language" id="s_language"> 
							<?php
							$languages = array();
							$languages[] = 'en_US.utf8';
							if ($handle = opendir('./../lang')) {
						    while (false !== ($entry = readdir($handle))) {
						      if ($entry != "." && $entry != "..") {
						        if (is_dir('./../lang/'.$entry)){
											$languages[] = $entry;
						        }
						      }
						    }
						    closedir($handle);
						  }
							sort($languages);
							foreach($languages as $language){
								?> <option <?php
								if ((isset($_SESSION['s_language']) and $language == $_SESSION['s_language']) or (!isset($_SESSION['s_language']) and $language == 'en_US.utf8')){
									?>selected<?php
								}
								?>><?php echo $language; ?></option><?php
							}
							?>
							</select> <img alt="Select one of the available languages (based on languages files present in 'lang' dir." src="./../img/info.png" />
						</div>
						<div class="input_group">
							<label>Date format</label>
							<select name="s_date_format" id="s_date_format"> 
							<?php
							$formats = array('d/m/Y', 'm/d/Y', 'Y/m/d');
							foreach($formats as $format){
								?> <option <?php
								if ((isset($_SESSION['s_date_format']) and $format == $_SESSION['s_date_format']) or (!isset($_SESSION['s_date_format']) and $format == 'Y/m/d')){
									?>selected<?php
								}
								?>><?php echo $format; ?></option><?php
							}
							?>
							</select> <img alt="The date format is the same as php date format. d = days, m = months, Y = year (4 digits)." src="./../img/info.png" />
						</div>
						<div class="form_buttons">
							<input type="submit" value="Back" name="back"/>
							<input type="submit" value="Next" name="submit"/>
							<input type="hidden" name="step" value="6"/>
						</div>
					</form>
					<?php
					}
					break;
				case 6:
					$result->ok = true;
					$result->message = '';
					if (!isset($_SESSION['message'])){
						$_SESSION['message'] = '';
					}
					if (isset($_POST['submit'])){
						if (isset($_POST['s_contests_name']) and !empty($_POST['s_contests_name'])){
							$_SESSION['s_contests_name'] = htmlspecialchars($_POST['s_contests_name']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Contests name is empty !</div>';
						}
						if (isset($_POST['s_gallery_only'])){
							$_SESSION['s_gallery_only'] = true;
						}else{
							$_SESSION['s_gallery_only'] = false;
						}
						if (isset($_POST['s_contest_disp_title']) and !empty($_POST['s_contest_disp_title'])){
							$_SESSION['s_contest_disp_title'] = htmlspecialchars($_POST['s_contest_disp_title']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Contest display title is empty !</div>';
						}
						if (isset($_POST['s_display_other_contests'])){
							$_SESSION['s_display_other_contests'] = true;
						}else{
							$_SESSION['s_display_other_contests'] = false;
						}
						if (isset($_POST['s_max_length']) and !empty($_POST['s_max_length'])){
							$_SESSION['s_max_length'] = intval($_POST['s_max_length']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Max thumbnail side size is empty !</div>';
						}
						if (isset($_POST['s_language']) and !empty($_POST['s_language'])){
							$_SESSION['s_language'] = htmlspecialchars($_POST['s_language']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Language is empty ! (How did you do that ?)</div>';
						}
						if (isset($_POST['s_date_format']) and !empty($_POST['s_date_format'])){
							$_SESSION['s_date_format'] = htmlspecialchars($_POST['s_date_format']);
						}else{
							$result->ok = false;
							$result->message .= '<div class="alert error">Date format is empty ! (How did you do that ?)</div>';
						}
					}
					if (!$result->ok){
						$_SESSION['message'] .= $result->message;
						header('Location: ?step=5');
					}else{
						/** Connection to DB. */
						$bd = mysql_connect($_SESSION['db_host'], $_SESSION['db_user'], $_SESSION['db_pwd']);
						if (!$bd){
							$result->ok = false;
							$result->message .= '<div class="alert error">Can\'t connect to MySQL Server !</div>';
						}else{
							$db_name = (isset($_SESSION['db_prefix'])) ? $_SESSION['db_prefix'].'_'.$_SESSION['db_name'] : $_SESSION['db_name'];
							$res = mysql_select_db($db_name, $bd);
							if (!$res){
								$result->ok = false;
								$result->message .= '<div class="alert error">Can\'t select database !</div>';
							}
						}
						/** Write settings in DB */
						$sql = 'INSERT INTO settings values("'.$_SESSION['s_contests_name'].'", '.((empty($_SESSION['s_gallery_only'])) ? 0 : 1 ).', "'.$_SESSION['s_contest_disp_title'].'", '.((empty($_SESSION['s_display_other_contests'])) ? 0 : 1).', '.$_SESSION['s_max_length'].', "'.$_SESSION['s_language'].'", "'.$_SESSION['s_date_format'].'", NULL)';
						$res = mysql_query($sql);
				    if (!$res) {
				      $sqlErrorCode = mysql_errno();
				      $sqlErrorText = mysql_error();
							$result->ok = false;
							$result->message .= '<div class="alert error">An error occured during settings save !<br/>Error code: '.$sqlErrorCode.'<br/>Error text: '.$sqlErrorText.'<br/>Statement:<br/>'.$sql.'</div>';
				    }
						$_SESSION['message'] .= $result->message;
						$config_array = array();
						$sample_file = file('./../config-sample.php', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
	          foreach ($sample_file as $line) {
	            if (stristr($line, '$mysql_hostname =')){
	              $line = '$mysql_hostname = "'.$_SESSION['db_host'].'";';
	            }elseif (stristr($line, '$mysql_user =')){
	              $line = '$mysql_user = "'.$_SESSION['db_user'].'";';
	            }elseif (stristr($line, '$mysql_password = ""')){
	              $line = '$mysql_password = "'.$_SESSION['db_pwd'].'";';
	            }elseif (stristr($line, '$mysql_database = ""')){
	              $line = '$mysql_database = "'.$_SESSION['db_name'].'";';
	            }elseif (stristr($line, '$mysql_user = ""')){
	              $line = '$mysql_user = "'.$_SESSION['db_user'].'";';
	            }elseif (stristr($line, '$prefix = ""')){
	              $line = '$prefix = "'.$_SESSION['db_prefix'].'";';
	            }elseif (stristr($line, '$c_path =')){
	              $line = '$c_path = "'.$_SESSION['photos_dir'].'";';
	            }elseif (stristr($line, 'COOKIE_NAME')){
	              $line = 'define("COOKIE_NAME", "'.$_SESSION['cookie_name'].'");';
	            }elseif (stristr($line, 'PASSWD')){
	              $line = 'define("PASSWD", "'.$_SESSION['admin_pwd'].'");';
	            }elseif (stristr($line, 'HASH')){
	              $line = 'define("HASH", "'.generateRandomString().'");';
	            }
							$config_array[] = $line.PHP_EOL;
	          }
						/** To reset last php error (if any) */
						@trigger_error("");
			      $file_temp = @fopen('./../config.php', 'x');
						if (!$file_temp){
							$error = error_get_last();
							$err_tab = explode(': ', $error['message'], 2);
							$_SESSION['message'] .= '<div class="alert error">'.$err_tab[1].'</div>';
							$result->ok = false;
						}
						if ($result->ok){
							foreach ($config_array as $line){
								$ret = fwrite($file_temp, $line);
								if (!$ret){
									$_SESSION['message'] .= '<div class="alert error">Unable to write into config.php file. It may be an insuffisant access rights problem.</div>';
									$result->ok = false;
									break;
								}
							}
				      fclose($file_temp);
							$_SESSION['message'] .= '<div class="alert success">The config.php file was successfully created !</div>';
						}
			      
						?>
						<h1>Install : Creating config file</h1>
						<form class="large" method="POST" action="install.php">
							<p>Installer has tried to create the config.php file with the values you entered in the previous steps.</p>
							<?php
							if (isset($_SESSION['message'])){
								echo $_SESSION['message'];
								unset ($_SESSION['message']);
							}
							if (!$result->ok){
							?>
							<p>As the installer failed to create the config file, please copy the code below and paste it into a file named config.php at the root of the spc directory.</p>
							<div class="code" >
							<code>
							<?php
							foreach ($config_array as $line){
								echo htmlspecialchars($line).'<br />';
							}
							?>
							</code>
							</div>
							<?php } ?>
							<div class="form_buttons">
								<input type="submit" value="Back" name="back"/>
								<input type="submit" value="Next" name="submit"/>
								<input type="hidden" name="step" value="7"/>
							</div>
						</form>
						<?php
					}
					break;
				case 7:
					$result->ok = true;
					$result->message = '';
					if (isset($_POST['submit'])){
						$f = @fopen('./../config.php',"r");
						if (!$f){
							$result->message = '<div class="alert error">Unable to open config.php file. Please verify that you have this file at the SPC root dir.</div>';
							$result->ok = false;
						}
					}
					if (!$result->ok){
						$_SESSION['message'] = $result->message;
						header('Location: ?step=6');
					}else{
						?>
						<h1>Install : Finished !</h1>
						<form class="large" method="POST" action="install.php">
							<h2>Congratulations ! You successfully installed Simple Photos Contest !</h2>
							<p>I strongly suggest you to delete the install folder, as if someone runs the installer again it may break your SPC install.</p>
							<div class="form_buttons">
								<a class="button" href="./../index.php" title="Go to SPC">Go to my Simple Photos Contest !</a>
							</div>
						</form>
						<?php
					}
					break;
			}
			?>
		</div>
		<script>
			var noFreetile = true;
		</script>
		<script type="text/javascript" src="./../js/jquery-1.8.2.min.js"></script>
		<script type="text/javascript" src="./../js/zebra_datepicker.js"></script>
		<script type="text/javascript" src="./../js/jquery.freetile.min.js"></script>
		<script type="text/javascript" src="./../js/contest.js"></script>
		<script type="text/javascript" src="install.js"></script>
	</body>
</html>
<?php
/**
* Used to generate a salt key.
* @param int $length Length of the returned string
* 
*/
function generateRandomString($length = 40) {    
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!#@&$*%?-+="), 0, $length);
}
?>