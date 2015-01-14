<?php
include("config.php");
$ip=$_SERVER['REMOTE_ADDR']; 

if(isset($_POST['id']) and !empty($_POST['id'])){
	$id= intval($_POST['id']);
	$contest= htmlspecialchars($_POST['contest']);
  $ret = mysqli_query($bd, "select * from contests where contest = '$contest'");
  if ($ret !== null){
    $contest_settings = mysqli_fetch_object($ret);
    if ($contest_settings->voting_type == "contest"){
      $ip_sql=mysqli_query($bd, "select ip_add from image_IP where contest = '$contest'");
    }else{
      $ip_sql=mysqli_query($bd, "select ip_add from image_IP where img_id_fk=$id and ip_add='$ip'");
    }
  	$count=mysqli_num_rows($ip_sql);
  	//var_dump($id);
  	if($count==0){
  		$sql = "UPDATE `images` SET love = love +1 WHERE img_id = ".$id;
  		//var_dump($sql);
  		mysqli_query($bd, $sql);
  		$sql_in = "insert into image_IP (ip_add,img_id_fk,contest) values ('$ip',$id,'$contest')";
  		mysqli_query($bd, $sql_in);
  		$result=mysqli_query($bd, "select love from images where img_id=$id");
  		//var_dump($result);
  		$row=mysqli_fetch_array($result);
  		$love=$row['love'];
  		?>
  		<span class="on_img" align="left"><?php echo $love; ?></span>
  		<?php
  	}else{
  		echo _('You have already voted !');
  	}
  }
}

if (isset($_POST['action'])){
	if ($_POST['action'] == 'login'){
		$pwd = $_POST['pwd'];
		if ($pwd == PASSWD){
			$ok = setcookie(COOKIE_NAME, sha1(PASSWD.HASH), 0, '/', '', FALSE, TRUE);
			if (!$ok){
        echo '<div class="alert error">cookie failed !</div>';
      }
		}else{
			echo '<div class="alert error"><a class="close" href="#" title="'._('Close').'">×</a>'._('Wrong password !').'</div>';
		}
	}
}
?>
