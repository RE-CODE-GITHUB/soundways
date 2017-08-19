<?php
session_start();
include("../db.php");

$mail=$_SESSION['soundways_user_mail'];
$pass=$_SESSION['soundways_user_pass'];
$ok=1;


/* !サーバDB接続! */
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
$result = mysql_query("SELECT user_id from soundways_user_info WHERE user_mail = '$mail' AND user_pass = '$pass' ");

while ($row = mysql_fetch_assoc($result)) {
	$user_id=$row['user_id'];
	$ok=0;
}

if($ok==1){
	mysql_close($link);
	header("Location: ../login.php");
}else{

	$v_ok=1;
	if(!empty($_GET["id"])){
		$followed_id=$_GET["id"];
	}else{
		$v_ok=0;
	}
	
	if(!empty($_GET["type"])){
		$url_type = $_GET["type"];
	}else{
		$v_ok=0;
	}	
	
	if($user_id == $followed_id){
		$v_ok=0;
	}

	if(!empty($_GET["del"])){
		$del_sw = $_GET["del"];
	}else{
		$del_sw=0;
	}

			


	if($url_type==1){
		$send_url="creator.php?id=".$followed_id;
	}else if($url_type==2){
		$send_url="following.php?id=".$followed_id;
	}else if($url_type==3){
		$send_url="follower.php?id=".$followed_id;
	}else if($url_type==4){
		$send_url="likes.php?id=".$followed_id;
	}else if($url_type==5){
		$send_url="detail.php?id=".$followed_id;
	}

	
	if($v_ok==1){
		
		if($del_sw==0){
			//追加
			
			//すでにフォローしているか
			$follow_ok=1;
			$result = mysql_query("SELECT * from soundways_following WHERE following_user_id = '".$user_id."' AND followed_user_id = '".$followed_id."'");
			while ($row = mysql_fetch_assoc($result)) {
				$follow_ok=0;
			}


			if($follow_ok==1){
			
				//フォロー数の加算
				$result = mysql_query("SELECT user_following_num from soundways_user_info WHERE user_id = '".$user_id."'");
				while ($row = mysql_fetch_assoc($result)) {
					$user_following_num=$row['user_following_num']+1;
				}
				$sql = "UPDATE soundways_user_info SET user_following_num = '$user_following_num' WHERE user_id='".$user_id."'";
				$result_flag = mysql_query($sql);
		
				//フォロワー数の加算
				$result = mysql_query("SELECT user_follower_num from soundways_user_info WHERE user_id = '".$followed_id."'");
				while ($row = mysql_fetch_assoc($result)) {
					$user_follower_num=$row['user_follower_num']+1;
				}
				$sql = "UPDATE soundways_user_info SET user_follower_num = '$user_follower_num' WHERE user_id='".$followed_id."'";
				$result_flag = mysql_query($sql);
				
				//フォローに追加する
				$sql = "INSERT INTO soundways_following (following_user_id,followed_user_id) VALUES ('$user_id','$followed_id')";
				$result_flag = mysql_query($sql);
			
				mysql_close($link);
				header("Location: ../".$send_url."&follow_sw=1");
			
			}else{
				mysql_close($link);
				header("Location: ../".$send_url."&follow_sw=0");
			}
		
		}else{
			//削除
			
			$sql = "DELETE FROM soundways_following WHERE following_user_id='".$user_id."' AND followed_user_id ='".$followed_id."'";
			$result_flag = mysql_query($sql);
			
			
			//フォロー数の減産
			$result = mysql_query("SELECT user_following_num from soundways_user_info WHERE user_id = '".$user_id."'");
			while ($row = mysql_fetch_assoc($result)) {
				if($row['user_following_num']>0){
					$user_following_num=$row['user_following_num']-1;
				}else{
					$user_following_num=0;
				}
			}
			$sql = "UPDATE soundways_user_info SET user_following_num = '$user_following_num' WHERE user_id='".$user_id."'";
			$result_flag = mysql_query($sql);
	
			//フォロワー数の減産
			$result = mysql_query("SELECT user_follower_num from soundways_user_info WHERE user_id = '".$followed_id."'");
			while ($row = mysql_fetch_assoc($result)) {
				if($row['user_follower_num']>0){
					$user_follower_num=$row['user_follower_num']-1;
				}else{
					$user_follower_num=0;
				}
			}
			$sql = "UPDATE soundways_user_info SET user_follower_num = '$user_follower_num' WHERE user_id='".$followed_id."'";
			$result_flag = mysql_query($sql);
			
			
			
			mysql_close($link);
			header("Location: ../".$send_url."&follow_sw=0");
		}

		
	}else{

		mysql_close($link);
		header("Location: ../".$send_url."&error=1");
	
	}

}

?>