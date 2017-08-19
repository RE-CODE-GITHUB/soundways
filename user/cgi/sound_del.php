<?php
session_start();
include("../../db.php");
$mail=$_SESSION['soundways_user_mail'];
$pass=$_SESSION['soundways_user_pass'];
$login_ok=0;
$v_ok=1;
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
$result = mysql_query("SELECT * from soundways_user_info WHERE user_mail = '$mail' AND user_pass = '$pass' ");

while ($row = mysql_fetch_assoc($result)) {
	$user_name=$row['user_name'];
	$user_id=$row['user_id'];
	$login_ok=1;
}


if($login_ok==1){


if(!empty($_POST["sound_id"])){
	$sound_id=$_POST["sound_id"];
}else{
	$sound_id="";
	$v_ok=0;
}


//////////////////////////////////////////////////////////



	if($v_ok){

		//::::アルゴリズム::::
		$result = mysql_query("SELECT * from soundways_sound WHERE sound_id='".$sound_id."' ");
		
		while ($row = mysql_fetch_assoc($result)) {
			$sound_filename=$row['sound_filename'];
			$sound_img=$row['sound_img'];
		}
		
		$jpg_filename = preg_replace("/(.+)(\.[^.]+$)/", "$1",$sound_img).".jpg";

		unlink("../../music/".$sound_filename);
		unlink("../../music_img/".$sound_img);
		unlink("../../music_img_thumb/".$jpg_filename);
	  	
	  	//tag
		$sql = "DELETE FROM soundways_sound WHERE sound_id = '$sound_id'";
		$result_flag = mysql_query($sql);
		
		//tagの削除
	  	$tag_del_r = mysql_query("SELECT * from soundways_sound_tag_box WHERE sound_id='".$sound_id."'");
	  	while ($tag_del_row = mysql_fetch_assoc($tag_del_r)) {
		  	
		  	$tag_del_r_d = mysql_query("SELECT * from soundways_sound_tag WHERE tag_id='".$tag_del_row["tag_id"]."'");
		  	while ($tag_del_row_d = mysql_fetch_assoc($tag_del_r_d)) {
				$tag_del_num = $tag_del_row_d["tag_num"]-1;
			}
		
			$sql = "DELETE FROM soundways_sound_tag_box WHERE sound_id = '".$tag_del_row["sound_id"]."'";
			$result_flag = mysql_query($sql);
	  		
	  		$sql = "UPDATE soundways_sound_tag SET tag_num = '".$tag_del_num."' WHERE tag_id='".$tag_del_row["tag_id"]."'";
	  		$result_flag = mysql_query($sql);
			
			
		}
		//サウンド数の減算
		$result3 = mysql_query("SELECT user_sound_num from soundways_user_info WHERE user_id ='".$user_id."'");
			while ($row3 = mysql_fetch_assoc($result3)) {
			$user_sound_num = $row3["user_sound_num"]-1;
		}
		$sql = "UPDATE soundways_user_info SET user_sound_num = '".$user_sound_num."' WHERE user_id ='".$user_id."'";
		$result_flag = mysql_query($sql);
		

		//コメント削除
		$sql = "DELETE FROM soundways_comment WHERE sound_id = '$sound_id'";
		$result_flag = mysql_query($sql);
		
		//返信削除
		$sql = "DELETE FROM soundways_comment_reply WHERE sound_id = '$sound_id'";
		$result_flag = mysql_query($sql);
		
		//通知削除
		$sql = "DELETE FROM soundways_activity WHERE sound_id = '$sound_id'";
		$result_flag = mysql_query($sql);
		
		//各ユーザー通知カウント減算
	  	$noti_del_r = mysql_query("SELECT * from soundways_notification_box WHERE sound_id='".$sound_id."'");
	  	while ($noti_del_row = mysql_fetch_assoc($noti_del_r)) {
		
			$result = mysql_query("SELECT user_notification_num from soundways_user_info WHERE user_id = '".$noti_del_row["user_id"]."'");
			while ($row = mysql_fetch_assoc($result)) {
				$user_notification_num=$row['user_notification_num']-1;
			}
			$sql = "UPDATE soundways_user_info SET user_notification_num = '".$user_notification_num."' WHERE user_id ='".$noti_del_row["user_id"]."'";
			$result_flag = mysql_query($sql);

		}
				
		//通知ボックス削除
		$sql = "DELETE FROM soundways_notification_box WHERE sound_id = '$sound_id'";
		$result_flag = mysql_query($sql);
		
		
		$sql = "UPDATE soundways_user_info SET user_notification_num = '$user_notification_num' WHERE user_id = '".$user_id."'";
		$result_flag = mysql_query($sql);
		
		//お気に入り削除
		$sql = "DELETE FROM soundways_likes WHERE sound_id = '$sound_id'";
		$result_flag = mysql_query($sql);

		//お気に入り削除
		$sql = "DELETE FROM soundways_goods_box WHERE sound_id = '$sound_id'";
		$result_flag = mysql_query($sql);

		//ストリームを切る
		mysql_close($link);	
		header("Location: ../sound.php");
		
	}else{
	
		//ストリームを切る
		mysql_close($link);	
		header("Location: ../sound_detail.php?id=".$sound_id."?error=1");
	}



}else{

		//ストリームを切る
		mysql_close($link);	
		header("Location: ../../login.php");
	
}

?>