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

if(!empty($_POST["sound_id"])){
	$sound_id=$_POST["sound_id"];
}else{
	$sound_id=0;
}

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


$mail = str_replace("'", "", $mail);
$mail = str_replace("\"", "", $mail);
$pass = str_replace("'", "", $pass);
$pass = str_replace("\"", "", $pass);

$result = mysql_query("SELECT * from soundways_user_info WHERE user_mail = '".$mail."' AND user_pass = '".$pass."'");
while ($row = mysql_fetch_assoc($result)) {
	$user_id=$row['user_id'];
}

$sound_time_ok=0;
$now_time = date("Y/n/d H:i:s");
if(!empty($_SESSION['user_last_sound_play_time_'.$sound_id])){

	$user_last_sound_play_time = $_SESSION['user_last_sound_play_time_'.$sound_id];
	//30秒前に押したかどうか
	if( ( strtotime($now_time) - strtotime($user_last_sound_play_time) ) > 30){
		$sound_time_ok=1;
	}
	
}else{

	$sound_time_ok=1;	

}


if($sound_time_ok==1){
	
  	$result = mysql_query("SELECT sound_plays from soundways_sound WHERE sound_id = '".$sound_id."'");
	while ($row = mysql_fetch_assoc($result)) {
		$sound_plays_num = $row["sound_plays"]+1;
	}
	
	$sql = "UPDATE soundways_sound SET sound_plays = '".$sound_plays_num."' WHERE sound_id = '".$sound_id."'";
	$result_flag = mysql_query($sql);
	
	$_SESSION['user_last_sound_play_time_'.$sound_id]=$now_time;

}


mysql_close($link);
?>