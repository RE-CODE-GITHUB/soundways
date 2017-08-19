<?php
include("../db.php");
require_once("../pear/PEAR/Mail.php");

mb_language("japanese");
mb_internal_encoding("UTF-8");
$params = array(
  "host" => "shareads.xsrv.jp",
  "port" => 587,
  "auth" => true,
  "username" => "info@soundways.jp",
  "password" => "dauhueh32j"
);


$error= Array(0,0,0,0,0,0,0);

$ok=1;



if(empty($_POST["mail"])){
	$mail="";
	$ok=0;
	$error[0]=1;
}else{
	$mail=$_POST["mail"];
}

$mail = str_replace("'", "", $mail);
$mail = str_replace("_", "'_'", $mail);
$mail = str_replace(">", "&gt", $mail);
$mail = str_replace("<", "&lt", $mail);



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


$result = mysql_query("SELECT * from soundways_user_info WHERE user_mail = '".$mail."'");
while ($row = mysql_fetch_assoc($result)) {
	$ok=0;
}




//移動
if($ok==1){

		$str = $mail."ijdaoij43fs";
		$mailhash = hash_hmac('sha256', $str, false);

		$sql = "INSERT INTO soundways_kari (mail,mail_hash) VALUES ('$mail','$mailhash')";
		$result_flag = mysql_query($sql);

		$mailObject = Mail::factory("smtp", $params);
		$recipients = $mail;
		$headers = array(
		  "To" => $mail,
		  "From" => "info@soundways.jp",
		  "Subject" => "【SoundWays】仮登録完了のお知らせ"
		);
		
$body = "ゲストユーザー様

SoundWaysの仮登録が完了したことをお知らせ致します。
下記のURLをクリックし、本登録をお願い致します。

http://soundways.jp/mainregister.php?mailid=".$mailhash."



※本メールは、SoundWaysにご登録の皆さまにお送りしております。

SoundWays - 立体音響プラットフォーム
http://soundways.jp
";

		
		$mailObject -> send($recipients, $headers, $body);

		//ストリームを切る
		mysql_close($link);

	header("Location: ../mailok.php?mail=".$mail);	

	
}else{

	header("Location: ../register.php?e0=".$error[0]."&e1=".$error[1]."&e2=".$error[2]);	

}





?>