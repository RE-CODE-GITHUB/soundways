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
	$creator_id=$_GET["id"];
}else{
	$creator_id=0;
}

$result = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$creator_id."'");
while ($row = mysql_fetch_assoc($result)) {
	$creator_name=$row['user_name'];
	$creator_profile=$row['user_profile'];
	$user_location=$row['user_location'];
	if(!empty($row['user_url'])){
		$creator_url=$row['user_url'];
		$creator_url_sw=1;	
	}else{
		$creator_url_sw=0;
	}
	if(empty($row["user_img"])){
		$creator_img= "tmp_img.jpg";
	}else{
		$creator_img=$row['user_img'];
	}
	
		
	if(empty($row["user_header_img"])){
		$creator_header_img= "tmp_header_img.jpg";
	}else{
		$creator_header_img=$row['user_header_img'];
	}
}



$following_r = mysql_query("SELECT * from soundways_following WHERE followed_user_id = '".$creator_id."'");
	
$follow_sw=0;
$follow_r = mysql_query("SELECT * from soundways_following WHERE followed_user_id = '".$creator_id."' AND following_user_id = '".$user_id."'");
while ($row = mysql_fetch_assoc($follow_r)) {
	$follow_sw=1;
}


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


$(".menu_hover").hover(function(){
	$(this).css("border-bottom","3px solid #ff5a5f");
/*
	$(this).animate({
		"",""
	},500);
*/
},function(){
	$(this).css("border-bottom","0px");
	
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
<div class="under_bar"></div>

<?php
include("views_parts/creator_header_area.php");
?>


<div style="background:white;width:100%;height:50px;line-height:50px;">
	<div style="width:1200px;margin:auto;font-size:14px;">
		<a href="creator.php?id=<?php echo $creator_id;?>" class="menu_hover" style="display:block;float:left;width:130px;text-align:center;color:#333;">
		<i class="fa fa-music" style='font-size:15px;margin-right:2px;'></i>
		3D SOUND
		</a>
		<a href="likes.php?id=<?php echo $creator_id;?>" class="menu_hover" style="display:block;float:left;width:130px;text-align:center;color:#333;">
		<i class="fa fa-heart" style='font-size:14px;margin-right:2px;'></i>
		お気に入り
		</a>
		<a href="following.php?id=<?php echo $creator_id;?>" class="menu_hover" style="display:block;float:left;width:120px;text-align:center;color:#333;">
		<i class="fa fa-plus" style='font-size:14px;margin-right:2px;'></i>
		フォロー
		</a>
		<a href="follower.php?id=<?php echo $creator_id;?>" class="menu_hover" style="display:block;float:left;width:130px;text-align:center;color:#ff5a5f;">
		<i class="fa fa-users" style='font-size:14px;margin-right:2px;'></i>
		フォロワー
		</a>
		
<?php

if($user_id != $creator_id){

	if($follow_sw==0){
	
		echo "<a href='cgi/follow.php?id=$creator_id&type=3' style='display:block;float:right;width:160px;margin-top:5px;background:#59c146;height:40px;line-height:40px;border-radius:10px;text-align:center;color:#fff;font-size:18px;'>フォローする</a>";
		
	}else{
	
		echo "<a href='cgi/follow.php?id=$creator_id&type=3&del=1' style='display:block;float:right;width:160px;margin-top:5px;background:#59c146;height:40px;line-height:40px;border-radius:10px;text-align:center;color:#fff;font-size:18px;'>フォロー中</a>";
		
	}

}else{
	echo "<a href='user/profile.php' style='display:block;float:right;width:170px;margin-top:10px;background:#59c146;height:30px;line-height:30px;border-radius:3px;text-align:center;color:#fff;font-size:14px;'>プロフィールを編集</a>";
}
?>

	</div>
</div>


<div style="background:#f3f3f3;width:100%;">
	<div style="width:1200px;margin:auto;padding:20px 0px;">
	
<?php

$following_count=0;
while ($row = mysql_fetch_assoc($following_r)) {
$following_count++;
$following_user_r = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$row["following_user_id"]."'");
while ($row2 = mysql_fetch_assoc($following_user_r)) {

	if(empty($row2["user_img"])){
		$follow_user_img= "tmp_img.jpg";
	}else{
		$follow_user_img=$row2["user_img"];
	}
	$user_name=$row2["user_name"];
	$user_profile=$row2["user_profile"];
	$f_user_id=$row2["user_id"];
	$user_follower_num=$row2["user_follower_num"];
	$user_sound_num=$row2["user_sound_num"];
}


if($following_count%2==1){
	echo "<div style='box-shadow:0px 0px 1px 1px #444;border-radius:3px;text-align:center;float:left;background:white;height:270px;width:210px;margin-right:20px;margin-bottom:20px;box-shadow:0px 0px 1px 0px #ddd;'>";
}else{
	echo "<div style='box-shadow:0px 0px 1px 1px #444;border-radius:3px;text-align:center;float:left;background:white;height:270px;width:210px;margin-bottom:20px;box-shadow:0px 0px 1px 0px #ddd;'>";
}
print<<<EOC
<p style='padding:15px 0px 10px 0px;'>
	<a href='creator.php?id={$f_user_id}'>
		<img src='userimg/$follow_user_img' style='border-radius:100px;width:130px;height:130px;'>
	</a>
</p>
<a href='creator.php?id={$f_user_id}' style='color:#333;'>{$user_name}</a>
<p style='cursor:pointer;border:1px solid #ccc;text-align:center;width:120px;border-radius:3px;padding:5px 10px;margin:10px auto;'>フォローする</p>
<p class='follow_button'>{$user_follower_num}フォロワー</p>
<div class="clear-fix"></div>
</div>
EOC;

/*
print<<<EOC
<img src='userimg/$follow_user_img' style='float:left;width:200px;height:200px;'>
<div style='width:390px;float:left;position:relative;'>
	<div style='padding:10px 10px;height:140px;'>
		<p style='font-size:25px;'><a href='creator.php?id=$f_user_id' style='color:#111;'>$user_name</a></p>
		<p style='font-size:20px;'>$user_follower_num followers</p>
		<p style='font-size:20px;'>$user_sound_num sounds</p>
	</div>
	<a href='' style='display:block;background:#333;text-align:center;width:100%;height:40px;line-height:40px;color:white;'>フォローする</a>
</div>
<div class="clear-fix"></div>
</div>
EOC;
*/
}

?>

	<div class="clear-fix"></div>
	</div>	
</div>



<?php
include("footer.php");
?>

<!-------------- page end -------------->
</div>
<!-- コメント -->
</body>
</html>
<?php
mysql_close($link);
?>