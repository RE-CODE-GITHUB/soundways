<?php
session_start();
include("db.php");
$mail=$_SESSION['soundways_user_mail'];
$pass=$_SESSION['soundways_user_pass'];
$login_ok=0;

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
$result = mysql_query("SELECT * from soundways_user_info WHERE user_mail = '".$mail."' AND user_pass = '".$pass."'");

while ($row = mysql_fetch_assoc($result)) {
	$user_name=$row['user_name'];
	$user_id=$row['user_id'];
	$user_notification_num=$row['user_notification_num'];
	if(empty($row["user_img"])){
		$user_img= "tmp_img.jpg";
	}else{
		$user_img=$row['user_img'];
	}
	$login_ok=1;
}

if($login_ok==1){

}else{
	$user_img="tmp_img.jpg";	
}



if(!empty($_GET["id"])){
	$sound_id=$_GET["id"];
}else{
	$sound_id=0;
}


if(!empty($_GET["s"])){
	$s=$_GET["s"];
}else{
	$s="";
}


if(mb_substr($s,0,1)==="#"){
	//タグ検索
	$tmp_s = str_replace("#", "",$s);
	//おすすめサウンド一覧
	$search_tag = mysql_query("SELECT * from soundways_sound_tag WHERE tag_text = '".$tmp_s."'");
	while ($row_tag = mysql_fetch_assoc($search_tag)) {
		$search_r_box = mysql_query("SELECT * from soundways_sound_tag_box WHERE tag_id = '".$row_tag["tag_id"]."' LIMIT 0,20");

	}

}else{
	//キーワード検索
	//おすすめサウンド一覧
	$search_r = mysql_query("SELECT * from soundways_sound WHERE sound_title LIKE '%".$s."%' LIMIT 0,20");

}




//おすすめクリエイター一覧
$search_creator_r = mysql_query("SELECT * from soundways_sound WHERE sound_title LIKE '%".$s."%' LIMIT 0,20");




//通知データベース
$soundways_top_activity_r = mysql_query("SELECT * from soundways_activity WHERE user_id = '".$user_id."' ORDER BY activity_id DESC LIMIT 0,4");



?><!DOCTYPE html>
<html lang="ja"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SoundWays</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="viewport" content="width=device-width">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<link href="" rel="shortcut icon" type="image/x-icon">
<link rel='stylesheet' type='text/css' href='design.css'>
<?php
include("views_parts/all_head.php");
?>
<script src="js/jquery-2.0.2.min.js" type="text/javascript"></script>
<script src="js/top_bar.js" type="text/javascript"></script>

<script type="text/javascript">
//jQuery
$(document).ready(function(){
//点滅
$("a").hover(
  function(){
    $(this).fadeTo("500","0.8");
  },function(){
    $(this).fadeTo("100","1");
  }
);


$(".top_bar").animate({
	"top":"0"
},250);
$(".top_bar").css("box-shadow","0px 0px 2px 0px #444");


$(".music_paly_btn").click(function(){
	$(".under_bar").animate({
		"bottom":"0"
	},250);
	$("#footer").css("padding-bottom","60px");	
});





});
</script>
</head>
<!------------------------------------------ body ----------------------------------------->
<body>
<div id="page">

<!-- -->
<?php
include("top_bar.php");
?>


<!-- -->
<div class="under_bar">
	
</div>
<div id="search_contents">

<div style="padding-top:20px;width:1100px;margin:auto;">
	<div style="float:left;width:49%;background:;">
		<h2 style="color:#333;font-size:25px;border-bottom:1px solid silver;">3D SOUND</h2>
		<div style="padding-top:20px;">
<?php

$search_sound_num=0;
if(mb_substr($s,0,1)==="#"){

while ($row_tag_box = mysql_fetch_assoc($search_r_box)) {

$search_r = mysql_query("SELECT * from soundways_sound WHERE sound_id = '".$row_tag_box["sound_id"]."'");
while ($row = mysql_fetch_assoc($search_r)) {
$search_sound_num=1;

$search_user_r = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$row["user_id"]."'");
while ($row2 = mysql_fetch_assoc($search_user_r)) {
$sound_user_name=$row2["user_name"];
}

$sound_img=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row["sound_img"]).".jpg";
print<<<EOC

		<div style='margin-bottom:20px;'>
			<a href='detail.php?id=$row[sound_id]'>
			<img src="music_img_thumb/$sound_img" style="width:180px;height:180px;float:left;">
			</a>
			<div style="float:left;width:320px;">
				<p style='font-size:25px;padding-left:10px;'><a href='detail.php?id=$row[sound_id]' style='color:#111;'>$row[sound_title]</a></p>
				<p style='font-size:20px;padding-left:10px;color:#555;'>$sound_user_name</p>
				<p style='font-size:20px;padding-left:10px;color:#555;margin-top:10px;'>
					<img src='img_icon/play_sound_num.png' style='width:40px;position:absolute;margin:-5px 0px 0px -5px;'>
					<span style='margin-left:40px;'>$row[sound_plays]</span>
				</p>
				<p style='font-size:20px;padding-left:10px;color:#555;margin-top:10px;'>
					<img src='img_icon/good_sound_num.png' style='width:30px;position:absolute;margin:0px 0px 0px 0px;'>
					<span style='margin-left:40px;'>$row[sound_goods]</span>
				</p>
			</div>
			<div class="clear-fix"></div>
		</div>
		
EOC;

}

}


}else{
while ($row = mysql_fetch_assoc($search_r)) {
$search_sound_num=1;

$search_user_r = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$row["user_id"]."'");
while ($row2 = mysql_fetch_assoc($search_user_r)) {
$sound_user_name=$row2["user_name"];
$sound_user_id=$row2["user_id"];
}

$sound_img=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row["sound_img"]).".jpg";
print<<<EOC

		<div style='margin-bottom:20px;'>
			<a href='detail.php?id=$row[sound_id]'>
			<img src="music_img_thumb/$sound_img" style="width:180px;height:180px;float:left;">
			</a>
			<div style="float:left;width:320px;">
				<p style='font-size:25px;padding-left:10px;'><a href='detail.php?id=$row[sound_id]' style='color:#111;'>$row[sound_title]</a></p>
				<p style='font-size:20px;padding-left:10px;color:#555;'>
				<a href='creator.php?id=$sound_user_id' style='color:#555;'>
				$sound_user_name 
				</a>
				</p>
				<p style='font-size:20px;padding-left:10px;color:#555;margin-top:10px;'>
					<img src='img_icon/play_sound_num.png' style='width:40px;position:absolute;margin:-5px 0px 0px -5px;'>
					<span style='margin-left:40px;'>$row[sound_plays]</span>
				</p>
				<p style='font-size:20px;padding-left:10px;color:#555;margin-top:10px;'>
					<img src='img_icon/good_sound_num.png' style='width:30px;position:absolute;margin:0px 0px 0px 0px;'>
					<span style='margin-left:40px;'>$row[sound_goods]</span>
				</p>
			</div>
			<div class="clear-fix"></div>
		</div>
		
EOC;

}

	
}

if($search_sound_num==0){
	echo $s."と一致するサウンドはありませんでした。";
}
?>
		

		
		</div>
	</div>
	<div style="float:right;width:49%;background:;">
		<h2 style="color:#333;font-size:25px;border-bottom:1px solid silver;">CREATOR</h2>
				<div style="padding-top:20px;">
<?php

while ($row = mysql_fetch_assoc($search_creator_r)) {


$search_user_r = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$row["user_id"]."'");
while ($row2 = mysql_fetch_assoc($search_user_r)) {
$sound_user_name=$row2["user_name"];
$sound_user_profile=$row2["user_profile"];
$sound_user_id=$row2["user_id"];
	if(empty($row2["user_img"])){
		$sound_user_img= "tmp_img.jpg";
	}else{
		$sound_user_img=$row2['user_img'];
	}
}

print<<<EOC

		<div style='margin-bottom:20px;'>
			<a href='creator.php?id=$sound_user_id'>
			<img src="userimg/$sound_user_img" style="width:180px;height:180px;float:left;">
			</a>
			<div style="float:left;width:320px;">
				<p style='font-size:25px;padding-left:10px;'>
				<a href='creator.php?id=$sound_user_id' style='color:#111;'>
				$sound_user_name
				</a>
				</p>
				<p style='font-size:18px;padding-left:10px;color:#555;'>$sound_user_profile</p>
			</div>
			<div class="clear-fix"></div>
		</div>
		
EOC;

}

?>
		

		
		</div>
		
	</div>
	<div class="clear-fix"></div>
</div>
</div>

<!-------------- page end -------------->
</div>
<!-- コメント -->
</body>
</html>
<?php
mysql_close($link);
?>