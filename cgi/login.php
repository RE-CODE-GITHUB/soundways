<?php
session_start();
include("../db.php");
$mail = htmlspecialchars($_POST["mail"],ENT_QUOTES);
$pass = htmlspecialchars($_POST["pass"],ENT_QUOTES);
$ok=0;

if(!empty($_POST["re_url"])){
	$re_url = $_POST["re_url"].".php";
}else{
	$re_url = "";
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

$result = mysql_query("SELECT * from soundways_user_info WHERE user_mail = '".$mail."' AND user_pass = '".$pass."'");

while ($row = mysql_fetch_assoc($result)) {
	$user_id=$row['id'];
	$ok=1;
}



if(empty($pass)){
	$ok=2;
}
if(empty($mail)){
	$ok=2;
}

$_SESSION['soundways_user_mail'] = $mail;
$_SESSION['soundways_user_pass'] = $pass;
/*
setcookie("noteler_da9e23sp_mailaddress", $mail, time()+1284000,"/","noteller.com");
setcookie("noteler_da9e23sp_account", $tenpo_account, time()+1284000,"/","noteller.com");
*/


//移動
if($ok==1){
	if(empty($re_url)){
		header("Location: ../user");
	}else{
		header("Location: ../$re_url");
	}
}else if($ok==2){
	header("Location: ../login.php?error=9&mail=".$mail);
}else{
	header("Location: ../login.php?error=2&mail=".$mail);
}


//ストリームを切る
mysql_close($link);
/* !サーバDB接続終了! */
?>