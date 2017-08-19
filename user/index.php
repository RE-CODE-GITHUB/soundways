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
$result = mysql_query("SELECT * from soundways_user_info WHERE user_mail = '$mail' AND user_pass = '$pass' ");

while ($row = mysql_fetch_assoc($result)) {
	$user_name=$row['user_name'];
	$user_id=$row['user_id'];
	$user_img=$row['user_img'];
	$user_notification_num=$row['user_notification_num'];
	$user_all_sound_play_num = $row['user_all_play_num'];
	$user_follower_num = $row['user_following_num'];
	$user_good_num = $row['user_good_num'];
	$ok=0;
}

if($ok==1){
	header("Location: ../login.php");
}else{


$soundways_activity_r = mysql_query("SELECT * from soundways_activity WHERE user_id = '".$user_id."' ORDER BY activity_id DESC LIMIT 0,10");

//通知データベース
$soundways_top_activity_r = mysql_query("SELECT * from soundways_activity WHERE user_id = '".$user_id."' ORDER BY activity_id DESC LIMIT 0,4");


}



?><!DOCTYPE html>
<html lang="ja"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>マイページ <?php echo $user_name;?>さん</title>
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


<div style="margin:auto;width:1000px;padding-top:80px;padding-bottom:20px;">
<?php include("sidebar.php"); ?>
	<div style="float:right;width:760px;">
	
		<div style="width:100%;background:white;padding:10px 0px 8px 0px;border-radius:6px;">
			<div style="margin-left:30px;width:120px;background:;text-align:center;float:left;">
				<img src="../userimg/<?php echo $user_img;?>" width="80px" height="80px" style="border-radius:50px;">
				<p><?php echo $user_name;?></p>
			</div>
			<div style="margin-top:20px;margin-left:30px;width:120px;background:;text-align:center;float:left;overflow:hidden;">
				<p style="color:#333;font-size:25px;"><?php echo $user_all_sound_play_num;?></p>
				<p style="color:#111;margin-top:20px;">再生回数</p>
			</div>
			<div style="margin-top:20px;margin-left:30px;width:120px;background:;text-align:center;float:left;">
				<p style="color:#333;font-size:25px;"><?php echo $user_follower_num;?></p>
				<p style="color:#111;margin-top:20px;">フォロワー数</p>
			</div>
			<div style="margin-top:20px;margin-left:30px;width:120px;background:;text-align:center;float:left;">
				<p style="color:#333;font-size:25px;"><?php echo $user_good_num;?></p>
				<p style="color:#111;margin-top:20px;">高評価数</p>
			</div>
			<div class="clear-fix"></div>
		</div>
		
		<div style="width:100%;background:white;margin-top:20px;min-height:304px;border-radius:6px;overflow:hidden;">
		
<?php
$act_sw=0;
while ($row2 = mysql_fetch_assoc($soundways_activity_r)) {
$act_sw=1;

$time=date('Y年n月d日 H:i',strtotime($row2["time"]));

if($row2["type"]==1){
	//サウンドへのコメント

	$result_u = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$row2["second_user_id"]."'");
	while ($row3 = mysql_fetch_assoc($result_u)) {
		$activity_user_name=$row3["user_name"];
		
		if(empty($row3["user_img"])){
			$activity_img = "../userimg/tmp_img.jpg";
		}else{
			$activity_img= "../userimg/".$row3["user_img"];
		}

	}
	
	$result_s_sound = mysql_query("SELECT * from soundways_sound WHERE sound_id = '".$row2["sound_id"]."'");
	while ($row3 = mysql_fetch_assoc($result_s_sound)) {
		$activity_sound_title = $row3["sound_title"];
	}

	$text = "<a href='../creator.php?id=".$row2["second_user_id"]."' style='color:#ff5a5f;'>".$activity_user_name."</a>さんが<a href='../detail.php?id=".$row2["sound_id"]."' style='color:#ff5a5f;'>【".$activity_sound_title."】</a>にコメントしました。";
	
	
}else if($row2["type"]==2){
	//サウンドアップロード

	$result_s_u = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$row2["user_id"]."'");
	while ($row3 = mysql_fetch_assoc($result_s_u)) {
		$activity_user_name=$row3["user_name"];
		//$activity_img = "../userimg/".$row3["user_img"];
	}
	
	$result_s_sound = mysql_query("SELECT * from soundways_sound WHERE sound_id = '".$row2["sound_id"]."'");
	while ($row3 = mysql_fetch_assoc($result_s_sound)) {
		$activity_sound_title = $row3["sound_title"];
		$activity_img = "../music_img_thumb/".preg_replace("/(.+)(\.[^.]+$)/", "$1",$row3["sound_img"]).".jpg";
	}
	
	$text = "<a href='../detail.php?id=".$row2["sound_id"]."' style='color:#ff5a5f;'>【".$activity_sound_title."】</a>をアップロードしました。";

}else if($row2["type"]==3){
	//コメント返信

	$result_s_u = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$row2["second_user_id"]."'");
	while ($row3 = mysql_fetch_assoc($result_s_u)) {
		$activity_user_name=$row3["user_name"];
		if(empty($row3["user_img"])){
			$activity_img = "../userimg/tmp_img.jpg";
		}else{
			$activity_img= "../userimg/".$row3["user_img"];
		}
	}
	
	$result_s_sound = mysql_query("SELECT * from soundways_sound WHERE sound_id = '".$row2["sound_id"]."'");
	while ($row3 = mysql_fetch_assoc($result_s_sound)) {
		$activity_sound_title = $row3["sound_title"];
	}
	
	
	$result_s_comment = mysql_query("SELECT * from soundways_comment WHERE comment_id = '".$row2["comment_id"]."'");
	while ($row3 = mysql_fetch_assoc($result_s_comment)) {
		$activity_sound_comment = $row3["comment"];
		$activity_sound_comment = str_replace("<br>", "", $activity_sound_comment);
		$activity_sound_comment= mb_strimwidth($activity_sound_comment, 0, 30, "...","utf8");
	
	}
	
	$text = $activity_user_name."さんが<a href='../detail.php?id=".$row2["sound_id"]."' style='color:#ff5a5f;'>【".$activity_sound_comment."】</a>に返信しました。";

}


print<<<EOC
			<div style="border-bottom:1px solid #eee;height:90px;padding:15px 0px 0px 0px;">
				<a href='../creator.php?id={$row2[second_user_id]}' style="line-height:70px;display:block;float:left;padding-left:15px;">
					<img src="$activity_img" width="50px" height="50px" style='vertical-align:middle;border-radius:50px;'>
				</a>
				<div style="float:left;padding-left:15px;padding-top:15px;">
					<p style="color:#222;">$text</p>
					<p style="color:#888;font-size:14px;margin-top:3px;">$time</p>
				</div>
				<div class="clear-fix"></div>
			</div>
EOC;
}			

if($act_sw==0){
	echo "<div style='padding:15px 0px 0px 15px;color:#888;font-size:16px;'>現在新規通知はありません</div>";
}
?>

			
			

		</div>
	
	</div>
	<div class="clear-fix"></div>
</div>



<!-------------- page end -------------->
</div>
<!-- コメント -->
</body>
</html>
<?php
mysql_close($link);
?>