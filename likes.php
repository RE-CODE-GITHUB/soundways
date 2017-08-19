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



$soundways_likes_r = mysql_query("SELECT * from soundways_goods_box WHERE user_id = '".$creator_id."' ORDER BY goods_id DESC LIMIT 0,20");
	
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
		<a href="likes.php?id=<?php echo $creator_id;?>" class="menu_hover" style="color:#ff5a5f;display:block;float:left;width:130px;text-align:center;">
		<i class="fa fa-heart" style='font-size:14px;margin-right:2px;'></i>
		お気に入り
		</a>
		<a href="following.php?id=<?php echo $creator_id;?>" class="menu_hover" style="display:block;float:left;width:120px;text-align:center;color:#333;">
		<i class="fa fa-plus" style='font-size:14px;margin-right:2px;'></i>
		フォロー
		</a>
		<a href="follower.php?id=<?php echo $creator_id;?>" class="menu_hover" style="display:block;float:left;width:130px;text-align:center;color:#333;">
		<i class="fa fa-users" style='font-size:14px;margin-right:2px;'></i>
		フォロワー
		</a>
		
<?php

if($user_id != $creator_id){

	if($follow_sw==0){
	
		echo "<a href='cgi/follow.php?id=$creator_id&type=4' style='display:block;float:right;width:160px;margin-top:5px;background:#59c146;height:40px;line-height:40px;border-radius:10px;text-align:center;color:#fff;font-size:18px;'>フォローする</a>";
		
	}else{
	
		echo "<a href='cgi/follow.php?id=$creator_id&type=4&del=1' style='display:block;float:right;width:160px;margin-top:5px;background:#59c146;height:40px;line-height:40px;border-radius:10px;text-align:center;color:#fff;font-size:18px;'>フォロー中</a>";
		
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

while ($row = mysql_fetch_assoc($soundways_likes_r)) {

	$sound_r = mysql_query("SELECT * from soundways_sound WHERE sound_id = '".$row["sound_id"]."'");
	while ($row2 = mysql_fetch_assoc($sound_r)) {
		$sound_img=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row2["sound_img"]).".jpg";
		$sound_id=$row2["sound_id"];
		$sound_title=$row2["sound_title"];
		$sound_user_id=$row2["user_id"];
		$sound_text=$row2["sound_text"];
		$sound_plays=$row2["sound_plays"];
		$sound_goods=$row2["sound_goods"];
		$time=$row2["time"];
	}
$time=date('Y年n月d日 H:i',strtotime($time));

	
	$u_r = mysql_query("SELECT * from soundways_user_info WHERE user_id='".$sound_user_id."'");
	while ($row2 = mysql_fetch_assoc($u_r)) {
		$user_name=$row2["user_name"];
	}
	
		
print<<<EOC
		
		<div class="music_area" style='background:;width:185px;height:220px;'>
			<div class='music_img_over'style='width:170px;'>
			<a href="detail.php?id={$sound_id}" style='width:170px;background-image:url(music_img_thumb/$sound_img);display:block;background-size:100% 100%;' class="music_img">
			<img src="img_icon/play_button.png" class="start_music_img">
			</a>
			</div>
			<div class="text_area">
				<p class="title" style='padding-top:3px;'><a href="detail.php?id={$sound_id}">{$sound_title}</a></p>
				<p class="user_name"><a href="creator.php?id=$sound_user_id">$user_name</a></p>
			</div>
		</div>
EOC;



/*
print<<<EOC
		<div style="background:white;box-shadow:0px 0px 1px 0px #ddd;margin-bottom:20px;">
			<div style="width:200px;float:left;height:200px;">
				<img src="music_img_thumb/$sound_img" style="width:200px;height:200px;box-shadow:0px 0px 1px 0px #ddd;">
			</div>
			<div style="width:900px;float:left;height:200px;">
				<p style="border-bottom:1px solid #ddd;height:40px;line-height:40px;font-size:20px;padding-left:20px;overflow:hidden;">
					<a href='detail.php?id=$sound_id' style='color:#333;'>$sound_title</a>
				</p>
				<div style="color:#666;height:85px;background:;font-size:16px;padding-left:20px;">
					<p style='max-height:65px;overflow:hidden;'>$sound_text</p>
					<p style="color:#888;font-size:13px;padding-top:3px;">
						$time
					</p>
				</div>
				<div class='creator_sound_tags' style='border-bottom:1px solid #ddd;height:30px;padding:3px 0px 0px 20px;'>
EOC;

//タグ一覧
$tag_r = mysql_query("SELECT * from soundways_sound_tag_box WHERE sound_id = '".$sound_id."'");
while ($row2 = mysql_fetch_assoc($tag_r)) {
	//タグ一覧
	$tag_r2 = mysql_query("SELECT tag_text from soundways_sound_tag WHERE tag_id='".$sound_id."'");
	
	while ($row3 = mysql_fetch_assoc($tag_r2)) {
		echo "<a href='search.php?=%23$row2[tag_text]' class='details_tags' style='margin-right:10px;'>#$row3[tag_text]</a>";
	}

}

print <<<EOC
			</div>
			
			<div style='height:40px;line-height:40px;padding:0px 20px;'>
				<div style='float:left;font-size:16px;margin-right:20px;'>$sound_plays plays</div>
				<div style='float:left;font-size:16px;'>$sound_goods gooods</div>
				<a href='' style='float:right;margin-top:5px;display:block;background:#ff5a5f;color:white;border-radius:20px;line-height:20px;padding:5px 50px;'>Likes</a>
				<div class="clear-fix"></div>
			</div>
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