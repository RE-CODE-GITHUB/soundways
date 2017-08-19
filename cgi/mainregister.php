<?php
session_start();
include("../db.php");
require_once("../pear/PEAR/Mail.php");

//初期化
$ok=1;
$error=0;
$hashok=0;

if(!empty($_POST["mailid"])){
	$mailid=$_POST["mailid"];
}else{
	$mailid="";
	$ok=0;
}

if(!empty($_POST["username"])){
	$username=$_POST["username"];
}else{
	$username="";
	$ok=0;
}

if(!empty($_POST["pass1"])){
	$pass1=$_POST["pass1"];
}else{
	$pass1="";
	$ok=0;
}

if(!empty($_POST["pass2"])){
	$pass2=$_POST["pass2"];
}else{
	$pass2="";
	$ok=0;
}


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


$result = mysql_query("SELECT * from soundways_kari WHERE mail_hash = '".$mailid."'");
while ($row = mysql_fetch_assoc($result)) {
	$hashok=1;
	$mail=$row["mail"];
}


//確認
$pass=$pass1;
if($pass1!=$pass2){$ok=0;$error=1;}
if(strlen($pass)<6 || strlen($pass)>100){$ok=0;$error=2;}

if(mb_ereg('[^0-9a-zA-Z?_]',$pass)){
	$ok=0;
}



if($ok==0 || $hashok==0){
	//ミス発見
	header("Location: ../mainregister.php?mailid=".$mailid."&username=".$username."&error=".$error) ;	
}else{
//変換
$username = str_replace(">", "&gt", $username);
$username = str_replace("<", "&lt", $username);
$mail = str_replace(">", "&gt", $mail);
$mail = str_replace("<", "&lt", $mail);


mb_language("japanese");
mb_internal_encoding("UTF-8");
$params = array(
  "host" => "shareads.xsrv.jp",
  "port" => 587,
  "auth" => true,
  "username" => "info@soundways.jp",
  "password" => "dauhueh32j"
);





$mailObject = Mail::factory("smtp", $params);
$recipients = $mail;
$headers = array(
"To" => $mail,
"From" => "info@soundways.jp",
"Subject" => "【SoundWays】登録完了のお知らせ"
);		

$body = $username."様

この度はSoundWaysのご登録ありがとうございました。
登録された内容は、以下の通りになります。

マイページ:http://soundways.jp/user

ユーザー名:".$username."

パスワード:".$pass."

※本メールは、SoundWaysにご登録の皆さまにお送りしております。

SoundWays - 立体音響プラットフォーム
http://soundways.jp
";


		$mailObject -> send($recipients, $headers, $body);
	

	$result = mysql_query('SELECT * from soundways_info WHERE id = 1');
	
	while ($row = mysql_fetch_assoc($result)) {
		$usernum=$row["user_num"]+1;
	}
	

	
	//代入
	$sql = "INSERT INTO soundways_user_info(user_id,user_name,user_mail,user_pass) VALUES ('$usernum','$username','$mail','$pass')";
	$result_flag = mysql_query($sql);	
	
	
	$sql = "UPDATE soundways_info SET user_num = '$usernum' WHERE id=1";
	$result_flag = mysql_query($sql);

	//仮登録削除
	$sql = "DELETE FROM soundways_kari WHERE mail = '$mail'";
	$result_flag = mysql_query($sql);
	
	
	
	$_SESSION['soundways_user_mail'] = $mail;
	$_SESSION['soundways_user_pass'] = $pass;
	//ストリームを切る
	mysql_close($link);


	header("Location: ../user");	
}	
		
		
?>