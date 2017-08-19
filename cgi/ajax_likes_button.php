<?php
session_start();
include("../db.php");
$mail=$_SESSION['soundways_user_mail'];
$pass=$_SESSION['soundways_user_pass'];

if(!empty($_POST["sound_id"])){
	$sound_id=$_POST["sound_id"];
}else{
	$sound_id=0;
}

$login_ok=0;
$likes_ok=0;
// !サーバDB接続! 
$link = mysql_connect($databasedomain,$databaseid,$databasepass);
if (!$link) {
    die('接続失敗です。'.mysql_error());
}
//データベース選択
$db_selected = mysql_select_db($databasename, $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}
//文字コード設定
mysql_set_charset('utf8');

//メールアドレスが登録されているかの判別.
$result = mysql_query("SELECT * from soundways_user_info WHERE user_mail = '".$mail."' AND user_pass = '".$pass."'");

while ($row = mysql_fetch_assoc($result)) {
	$user_id=$row['user_id'];
	$login_ok=1;
}


	if($login_ok==1){
		
		//すでにあるかどうか
		$result = mysql_query("SELECT * from soundways_likes WHERE sound_id = '".$sound_id."' AND user_id = '".$user_id."'");
		while ($row = mysql_fetch_assoc($result)) {
			$likes_ok=1;
		}

		if($likes_ok==0){
		  	
		  	$result = mysql_query('SELECT * from soundways_info');
			while ($row = mysql_fetch_assoc($result)) {
				$likes_id = $row["likes_num"]+1;
			}
			
			$sql = "UPDATE soundways_info SET likes_num = '$likes_id' WHERE id='1'";
			$result_flag = mysql_query($sql);
					
					
		  	$sql = "INSERT INTO soundways_likes(likes_id,user_id,sound_id) VALUES ('$likes_id','$user_id','$sound_id')";
		  	$result_flag = mysql_query($sql);
		  	
		  	$array=array("flg"=>1);
		  	
		 }else{
		 	
		 	$result = mysql_query('SELECT * from soundways_info');
			while ($row = mysql_fetch_assoc($result)) {
				$likes_id = $row["likes_num"]-1;
			}
			
			$sql = "UPDATE soundways_info SET likes_num = '$likes_id' WHERE id='1'";
			$result_flag = mysql_query($sql);
		  				
			$sql = "DELETE FROM soundways_likes WHERE user_id='".$user_id."' AND sound_id ='".$sound_id."'";
			$result_flag = mysql_query($sql);
			
		  	$array=array("flg"=>0);
		  	
		 }
	  	
	 }else{
	 
	  	$array=array("flg"=>0);
		
	}




echo json_encode($array);


mysql_close($link);

?>