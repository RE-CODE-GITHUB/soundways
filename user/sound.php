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

//メールアドレスが登録されているかの判別
$result = mysql_query("SELECT * from soundways_user_info WHERE user_mail = '$mail' AND user_pass = '$pass' ");

while ($row = mysql_fetch_assoc($result)) {
	$user_name=$row['user_name'];
	$user_id=$row['user_id'];
	$user_img=$row['user_img'];
	$user_notification_num=$row['user_notification_num'];
	$ok=0;
}

if($ok==1){
	header("Location: ../login.php");
}else{

if(!empty($_GET["type"])){
	$type=$_GET["type"];
}else{
	$type=0;
}

if($type==1){
	//投稿時間
}else if($type==2){
	//再生数
}else if($type==3){
	//再生時間
}else if($type==4){
	//高評価
}

$sound_r = mysql_query("SELECT * from soundways_sound WHERE user_id='".$user_id."' ORDER BY sound_id DESC");


//通知データベース
$soundways_top_activity_r = mysql_query("SELECT * from soundways_activity WHERE user_id = '".$user_id."' ORDER BY activity_id DESC LIMIT 0,4");



}



?><!DOCTYPE html>
<html lang="ja"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>temp</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<link href="" rel="shortcut icon" type="image/x-icon">
<link rel='stylesheet' type='text/css' href='design.css'>
<?php
include("views_parts/all_head.php");
?>
<script src="http://hukumoto.pe-gawa.com/js/jquery-2.0.2.min.js" type="text/javascript"></script>
<script src="../js/top_bar.js" type="text/javascript"></script>

<script type="text/javascript">
//jQuery
$(document).ready(function(){


});
</script>
</head>
<!------------------------------------------ body ----------------------------------------->
<body>
<div id="page">

<?php include("header.php"); ?>


<div style="margin:auto;width:1000px;padding-top:80px;">
<?php include("sidebar.php"); ?>
	<div style="float:right;width:770px;background:white;border-radius:5px;">
		<div style="padding:20px 10px;">
		<a href="sound.php?type=1" style="width:160px;display:block;text-align:center;border-radius:20px;padding:10px 0px;color:white;background:#ff5a5f;float:left;">
			投稿順
		</a>
		
		<a href="sound.php?type=2" style="margin-left:20px;width:160px;display:block;text-align:center;border-radius:20px;padding:10px 0px;color:white;background:#666;float:left;">
			再生回数順
		</a>
		
		<a href="sound.php?type=3" style="margin-left:20px;width:160px;display:block;text-align:center;border-radius:20px;padding:10px 0px;color:white;background:#666;float:left;">
			再生時間順
		</a>
		
		<a href="sound.php?type=4" style="margin-left:20px;width:160px;display:block;text-align:center;border-radius:20px;padding:10px 0px;color:white;background:#666;float:left;">
			高評価順
		</a>
		<div class="clear-fix"></div>
		
		<div style="padding-top:30px;">
<?php

while ($row = mysql_fetch_assoc($sound_r)) {

$time=date('Y年n月d日 H:i',strtotime($row["time"]));
$sound_img=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row["sound_img"]).".jpg";

print <<<EOC
	
	<div style="width:150px;height:200px;background:white;float:left;margin-right:20px;position:relative;">
<a href='sound_detail.php?id=$row[sound_id]' style='display:block;background-image:url(../img/modal_back.png);font-size:20px;color:white;text-align:center;width:100%;position:absolute;padding:15px 0px;margin-top:50px;'>申請中</a>
		<a href='sound_detail.php?id=$row[sound_id]'><img src="../music_img_thumb/$sound_img" style="width:150px;height:150px;box-shadow:0px 0px 2px 0px #888;"></a>
		<div style="font-size:14px;">
			<p style='height:20px;overflow:hidden;'>
			<a href='sound_detail.php?id=$row[sound_id]' style='color:#1a1a1a;'>$row[sound_title]</a>
			</p>
			<p style="font-size:12px;color:dimgray;">$time</p>
		</div>
	</div>
			
EOC;

}
?>

				<div class="clear-fix"></div>
		</div>
		</div>
	</div>
	<div class="clear-fix"></div>
</div>

<?php include("footer.php");?>

<!-------------- page end -------------->
</div>
<!-- コメント -->
</body>
</html>