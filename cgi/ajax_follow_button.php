<?php
session_start();
include("../db.php");
if(!empty($_SESSION["soundways_user_mail"])){
	$mail=$_SESSION['soundways_user_mail'];
}else{
	exit();
}

if(!empty($_SESSION['soundways_user_pass'])){
	$pass=$_SESSION['soundways_user_pass'];
}else{
	exit();
}

if(!empty($_POST["creator_user_id"])){
	$creator_user_id=$_POST["creator_user_id"];
}else{
	$creator_user_id=0;
}

$login_ok=0;
$creator_user_ok=0;
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
		$follow_ok=1;
		$result = mysql_query("SELECT * from soundways_following WHERE following_user_id = '".$user_id."' AND followed_user_id = '".$creator_user_id."'");
		while ($row = mysql_fetch_assoc($result)) {
			$follow_ok=0;
		}
		
		$follow_user_ok=1;
		if($user_id == $creator_user_id){
			$follow_user_ok=0;
		}
		
		if($follow_user_ok==1){
	
			if($follow_ok==1){
			
			  	$result = mysql_query('SELECT * from soundways_user_info WHERE user_id = '.$creator_user_id);
				while ($row = mysql_fetch_assoc($result)) {
					$follow_id = $row["user_follower_num"]+1;
				}
				
				$sql = "UPDATE soundways_user_info SET user_follower_num = '".$follow_id."' WHERE user_id = '".$creator_user_id."'";
				$result_flag = mysql_query($sql);
						
						
			  	$result = mysql_query('SELECT * from soundways_user_info WHERE user_id = '.$user_id);
				while ($row = mysql_fetch_assoc($result)) {
					$following_id = $row["user_following_num"]+1;
				}
				
				$sql = "UPDATE soundways_user_info SET user_following_num = '".$following_id."' WHERE user_id = '".$user_id."'";
				$result_flag = mysql_query($sql);
	
	
				//フォローに追加する
				$sql = "INSERT INTO soundways_following (following_user_id,followed_user_id) VALUES ('$user_id','$creator_user_id')";
				$result_flag = mysql_query($sql);
				
			  	
			  	$array=array("flg"=>1,"error_num"=>1,"follower_num"=>$follow_id);
			  	
			 }else{
			 
			 	$result = mysql_query('SELECT * from soundways_user_info WHERE user_id = '.$creator_user_id);
				while ($row = mysql_fetch_assoc($result)) {
					$follow_id = $row["user_follower_num"]-1;
				}
				
				$sql = "UPDATE soundways_user_info SET user_follower_num = '".$follow_id."' WHERE user_id = '".$creator_user_id."'";
				$result_flag = mysql_query($sql);
						
						
			  	$result = mysql_query('SELECT * from soundways_user_info WHERE user_id = '.$user_id);
				while ($row = mysql_fetch_assoc($result)) {
					$following_id = $row["user_following_num"]-1;
				}
				
				$sql = "UPDATE soundways_user_info SET user_following_num = '".$following_id."' WHERE user_id = '".$user_id."'";
				$result_flag = mysql_query($sql);
				
				
				$sql = "DELETE FROM soundways_following WHERE following_user_id='".$user_id."' AND followed_user_id ='".$creator_user_id."'";
				$result_flag = mysql_query($sql);
			 
			 
			  	$array=array("flg"=>0,"error_num"=>1,"follower_num"=>$follow_id);
			  	
			 }
			 
		  }else{
			  
			  	$array=array("flg"=>0,"error_num"=>0);

		  }
		  
	 }else{
	 
	  	$array=array("flg"=>0,"error_num"=>0);
		
	}




echo json_encode($array);


mysql_close($link);

?>