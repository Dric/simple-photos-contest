<?php
include("config.php");
$ip=$_SERVER['REMOTE_ADDR']; 

if($_POST['id']){
	$id= intval($_POST['id']);
	$contest= htmlspecialchars($_POST['contest']);
	$ip_sql=mysql_query("select ip_add from image_IP where img_id_fk=$id and ip_add='$ip'");
	$count=mysql_num_rows($ip_sql);
	//var_dump($id);
	if($count==0){
		$sql = "UPDATE `images` SET love = love +1 WHERE img_id = ".$id;
		//var_dump($sql);
		mysql_query( $sql);
		$sql_in = "insert into image_IP (ip_add,img_id_fk,contest) values ('$ip',$id,'$contest')";
		mysql_query( $sql_in);
		
		$result=mysql_query("select love from images where img_id=$id");
		//var_dump($result);
		$row=mysql_fetch_array($result);
		$love=$row['love'];
		?>
		<span class="on_img" align="left"><?php echo $love; ?></span>
		<?php
	}else{
		echo 'Vous avez déjà voté !';
	}
}
?>
