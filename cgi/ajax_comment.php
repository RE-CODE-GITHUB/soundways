<?php
session_start();
include("../db.php");

if(!empty($_SESSION["soundways_user_mail"])){
	$mail=$_SESSION['soundways_user_mail'];
}else{
$mail="";
}

if(!empty($_SESSION['soundways_user_pass'])){
	$pass=$_SESSION['soundways_user_pass'];
}else{
	$pass="";
}
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

$mail = str_replace("'", "", $mail);
$mail = str_replace("\"", "", $mail);
$pass = str_replace("'", "", $pass);
$pass = str_replace("\"", "", $pass);

//メールアドレスが登録されているかの判別.
$result = mysql_query("SELECT user_id from soundways_user_info WHERE user_mail = '$mail' AND user_pass = '$pass' ");

while ($row = mysql_fetch_assoc($result)) {
	$user_id=$row['user_id'];
	$ok=0;
}

if($ok==1){
	mysql_close($link);
	$array=array("error_num"=>3);
}else{

	$v_ok=1;
	if(!empty($_POST["sound_id"])){
		$sound_id=$_POST["sound_id"];
	}else{
		$sound_id=0;
		$v_ok=0;
	}
	if(!empty($_POST["text"])){
		$text=$_POST["text"];
	}else{
		$text="";
		$v_ok=0;
	}
	
	
$text = str_replace(">", "&gt", $text);
$text = str_replace("<", "&lt", $text);
$text = str_replace("
", "<br>", $text);

$sound_id = str_replace(">", "&gt", $sound_id);
$sound_id = str_replace("<", "&lt", $sound_id);
$sound_id = str_replace("'", "", $sound_id);
$sound_id = str_replace("\"", "", $sound_id);

	if($v_ok==1){

		$result = mysql_query("SELECT comment_num from soundways_info WHERE id = '1'");

		while ($row = mysql_fetch_assoc($result)) {
			$comment_id=$row['comment_num']+1;
			$ok=0;
		}

		$sql = "UPDATE soundways_info SET comment_num = '$comment_id' WHERE id='1'";
		$result_flag = mysql_query($sql);

		$time = date("Y-n-d H:i:s");
		$sql = "INSERT INTO soundways_comment (comment_id,user_id,sound_id,comment,time) VALUES ('$comment_id','$user_id','$sound_id','$text','$time')";
		$result_flag = mysql_query($sql);
		
	  	
	  	$ss_result = mysql_query("SELECT user_id from soundways_sound WHERE sound_id='".$sound_id."' ");
		while ($row_s = mysql_fetch_assoc($ss_result)) {
			$r_user_id =$row_s["user_id"];
		}
		
		
		$a_user_id_sw=0;
		//自分かどうかの確認
		if($r_user_id == $user_id){
			$a_user_id_sw=1;
		}
		
		if($a_user_id_sw==0){
		  	//通知
		  	$sql = "INSERT INTO soundways_activity(user_id,time,type,sound_id,second_user_id) VALUES ('$r_user_id','$time','1','$sound_id','$user_id')";
		  	$result_flag = mysql_query($sql);
		  	
		  	
		  	//notification数の加算
			$result = mysql_query("SELECT user_notification_num from soundways_user_info WHERE user_id = '".$r_user_id."'");
			while ($row4 = mysql_fetch_assoc($result)) {
				$user_notification_num=$row4['user_notification_num']+1;
			}
			
			$sql = "UPDATE soundways_user_info SET user_notification_num = '$user_notification_num' WHERE user_id = '".$r_user_id."'";
			$result_flag = mysql_query($sql);
		
			//notificationデータの追加
		  	$sql = "INSERT INTO soundways_notification_box(user_id,time,type,sound_id,fire_user_id) VALUES ('$r_user_id','$time','1','$sound_id','$user_id')";
		  	$result_flag = mysql_query($sql);
		  	
		  	
	  	}
	  	
		$array=array("error_num"=>1);
		mysql_close($link);
	}else{
	
		$array=array("error_num"=>2);
		mysql_close($link);
	}

}

echo json_encode($array);

?>