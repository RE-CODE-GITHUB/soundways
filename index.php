<?php
session_start();
include("db.php");
$mail=$_SESSION['soundways_user_mail'];
$pass=$_SESSION['soundways_user_pass'];
$login_ok=0;

$link = mysql_connect($databasedomain,$databaseid,$databasepass);
if (!$link) {
    die('接続失敗です。'.mysql_error());
}
$db_selected = mysql_select_db($databasename, $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}
mysql_set_charset('utf8');
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
if(!empty($_GET["t"])){
	$main_s=$_GET["t"];
	$main_s_sw=0;
	$main_s = str_replace(">", "&gt", $main_s);
	$main_s = str_replace("<", "&lt", $main_s);
	$main_s = str_replace("'", "", $main_s);
	$main_s = str_replace("\"", "", $main_s);
}else{
	$main_s_sw=1;
}


if(!empty($_GET["u"])){
	$main_u=$_GET["u"];
	$main_u_sw=0;
	$main_u = str_replace(">", "&gt", $main_u);
	$main_u = str_replace("<", "&lt", $main_u);
	$main_u = str_replace("'", "", $main_u);
	$main_u = str_replace("\"", "", $main_u);
}else{
	$main_u_sw=1;
}

if( $main_s_sw == 0 ){
	$main_tag_name_sound_r = mysql_query("SELECT * from soundways_sound_tag WHERE tag_text='".$main_s."'");
	while ($row = mysql_fetch_assoc($main_tag_name_sound_r)) {
		$main_s_id=$row["tag_id"];
	}
	$main_tag_sound_r = mysql_query("SELECT * from soundways_sound_tag_box WHERE tag_id='".$main_s_id."' ORDER BY sound_id DESC LIMIT 0,15");
}else if( $main_u_sw == 0 ){
	$main_user_sound_r = mysql_query("SELECT user_id from soundways_user_info WHERE user_name = '".$main_u."'");
	while ($row_u = mysql_fetch_assoc($main_user_sound_r)) {
		$seach_user_id=$row_u["user_id"];
	}
}
$left_tag_sound_r = mysql_query("SELECT * from soundways_sound_tag ORDER BY tag_num DESC LIMIT 0,6");
$left_creator_sound_r = mysql_query("SELECT * from soundways_user_info ORDER BY user_sound_num DESC LIMIT 0,6");
$soundways_top_activity_r = mysql_query("SELECT * from soundways_activity WHERE user_id = '".$user_id."' ORDER BY activity_id DESC LIMIT 0,4");

?><!DOCTYPE html>
<html lang="ja"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SoundWays</title>
<meta name="keywords" content=""/>
<meta name="description" content=""/>
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

<?php if($login_ok==0){?>
	var gameheight = $(window).height();
	var undergameheight=0;
	if(gameheight >= 720){
		undergameheight=720;
		$("#main_area").css("height","720px");
	}else if(gameheight < 720 && gameheight >= 505){
		undergameheight=gameheight;
		$("#main_area").css("height",gameheight+"px");
	}else{
		undergameheight=720;
		$("#main_area").css("height","720px");
	}


	$(".under_go_btn").click(function(){
		$("body").animate({scrollTop:undergameheight-50});
	});
	<?php
	if($main_s_sw==0 || $main_u_sw==0 ){
		echo "$('body').scrollTop(undergameheight-50);";
print <<<EOC

		hyoujisw=1;
		$(".top_bar").css("top","0");
		$(".top_bar").css("box-shadow","0px 0px 2px 0px #444");
		
EOC;
	}
	?>

	$(".under_go_btn").css("marginTop",(undergameheight-90)+"px");

var hyoujisw=0;
$(window).scroll(function () {
var s = $(this).scrollTop();
var m = $(window).height()-250;
	if(hyoujisw==0 && s >= m) {	
		hyoujisw=1;
		$(".top_bar").animate({
			"top":"0"
		},250);
		$(".top_bar").css("box-shadow","0px 0px 2px 0px #444");
	}
	
	if(hyoujisw==1 && s < m){
		hyoujisw=0;
		$(".top_bar").animate({
			"top":"-50px"
		},250);
		
		$(".top_bar").css("box-shadow","none");		
		$(".user_icon_menu").fadeOut();	
	}
});


<?php }else{ ?>

$(".top_bar").css("top","0");

<?php } ?>

$(".over_music_btn_hover").click(function(){
	
	$(".under_bar").animate({
		"bottom":"0"
	},250);
	$("#footer").css("padding-bottom","60px");	
});
	
	
$(".over_music_btn_hover").hover(function(){
	$(this).children(".over_music_btn").show();
	$(this).children(".over_music_btn").children(".start_music_img").show();
},function(){
	$(".over_music_btn").hide();
	$(".start_music_img").hide();
});

//サウンドホバー処理
move_img_up=5;
$(".music_img").hover(function(){
	$(this).animate({/*

		"background-positionX":"-="+move_img_up+"px",
		"background-positionY":"-="+move_img_up+"px",
		"width":"+="+(move_img_up*2)+"px",
		"height":"+="+(move_img_up*2)+"px",
*/
		"opacity":"0.9"
	},200);
	
	$(this).children(".start_music_img").fadeIn(200);
	
},function(){
	$(this).animate({/*

		"background-positionX":"+="+move_img_up+"px",
		"background-positionY":"+="+move_img_up+"px",
		"width":"-="+(move_img_up*2)+"px",
		"height":"-="+(move_img_up*2)+"px",
*/
		"opacity":"1"
	},200);
	
	$(this).children(".start_music_img").fadeOut(200);

	
});


//タグホバー処理	
$(".over_left_tag").hover(function(){
	if($(this).attr("id") !== "now_tag"){
		$(this).css("background","#D1D1D1");
	}
},function(){
	if($(this).attr("id") !== "now_tag"){
		$(this).css("background","#E5E5E5");
	}
});


$(window).on('load resize', function(){

	right_area_size = $(window).width()-275;
	right_area_size_detail = Math.floor(right_area_size/5)-17.5;
	//alert(right_area_size);
	if($(window).width() > 1200){
		//alert(right_area_size_detail);
		$(".music_area").css({
			"width":right_area_size_detail+"px",
			"height":(right_area_size_detail+45)+"px"
		});
		$(".music_img").css({
			"height":right_area_size_detail+"px",
			"width":right_area_size_detail+"px"
		});

		$(".music_img_over").css({
			"height":right_area_size_detail+"px"
		});
		
		$(".main_left_right_area").css({
			"height":(right_area_size_detail*3+195)+"px"
		});

		
		$(".contents_left_menu_area").css({
			"height":(right_area_size_detail*3+195)+"px"
		});

	}else{
		$(".music_area").css({
			"width":"170px",
			"height":"210px"
		});
		
	}

});


//padding:15px 15px 0px 15px;
//right_area_size/5
//.music_img{



});
</script>
</head>
<!------------------------------------------ body ----------------------------------------->
<body>
<div id="page">

<?php
include("top_bar.php");
?>

<!-- -->
<div class="under_bar">
	
</div>
<?php if($login_ok==0){?>
<div id="main_area">
		
	<div class="under_go_btn">
		<img src="img_icon/soundways_top_down_button.png" style="width:70px;">
	</div>
	
	<div id="menu">
		<div id="left_menu">
			<a href="./"><h2 style="margin-top: 8px;">SoundWays</h2></a>
		</div>
		<div id="right_menu">
			<ul>
			<li><a href="soundways.php">SoundWaysとは</a></li>
			<li><a href="login.php">ログイン</a></li>
			<li><a href="register.php" style="background:#ff5a5f;border-radius:20px;padding:8px 30px;">無料会員登録</a></li>
			</ul>
		</div>
		<div class="clear-fix"></div>
		<div style="background:white;width:94%;margin:auto;margin-top:20px;height:3px;"></div>
	</div>
	
	
	<div id="top_area">
		<h2 class="top_main_text" style="font-weight: 100;">世界初 3D SOUND 専用プラットフォーム</h2>
		<form action="search.php" method="get" class="top_form">
			<img src="img_icon/search_icon.png" class="top_submit2">
			<input type="text" name="s" class="searchtext" style="text-align:center;" placeholder="3D SOUNDを検索する" required>
		</form>
	</div>
	
	<div style="position:absolute;width:100%;bottom:25%;">
		<a href="register.php" class="top_register_button">
			30秒でカンタンに登録できる
		</a>
	</div>
</div>

<?php }else{ ?>
<div style="height:50px;width:100%;"></div>
<?php }?>

<div class="main_left_right_area" style="background:;height:715px;">
	<div class="contents_left_menu_area" style="float:left;background:#e5e5e5;width:275px;height:715px;box-shadow:0px 0px 1px 0px #999;">
		<div style="padding:20px 15px 10px 15px;">
			<p style="border-bottom:1px solid #ccc;font-size:16px;color:#666;padding-bottom:5px;">人気タグ</p>
		</div>
		<div style="font-size:14px;">
<?php

while ($row = mysql_fetch_assoc($left_tag_sound_r)) {
if($row["tag_id"] != $main_s_id){
	
print<<<EOC
			<a href="?t=$row[tag_text]" class="over_left_tag" style="display:block;cursor:pointer;background:;color:#666;width:100%;height:40px;line-height:40px;">
				<p style="padding:0px 20px;">#$row[tag_text]<i class="fa fa-angle-right" style="font-size:25px;color:#999;float:right;margin-top:8px;"></i></p>
			</a>
EOC;

}else{

print<<<EOC
			<a href="?t=$row[tag_text]" id='now_tag' class="over_left_tag" style="display:block;cursor:pointer;background:#ff5a5f;color:#fff;width:100%;height:40px;line-height:40px;">
				<p style="padding:0px 20px;">#$row[tag_text]<i class="fa fa-angle-right" style="font-size:25px;color:#fff;float:right;margin-top:8px;"></i></p>
			</a>
EOC;
	
}

}

?>

		</div>
		
		<div style="padding:20px 15px 10px 15px;">
			<p style="border-bottom:1px solid #ccc;font-size:16px;color:#666;padding-bottom:5px;">人気アーティスト</p>
		</div>
			<div style="font-size:14px;">

<?php

while ($row = mysql_fetch_assoc($left_creator_sound_r)) {
	
if($row["user_id"] != $main_u){
	
print<<<EOC
			<a href='?u=$row[user_id]' class="over_left_tag" style="display:block;cursor:pointer;background:;color:#666;width:100%;height:40px;line-height:40px;">
				<p style="padding:0px 20px;">$row[user_name]<i class="fa fa-angle-right" style="font-size:25px;color:#999;float:right;margin-top:8px;"></i></p>
			</a>
EOC;

}else{
	
print<<<EOC
			<a href='?u=$row[user_id]' id='now_tag' class="over_left_tag" style="display:block;cursor:pointer;background:#ff5a5f;color:#fff;width:100%;height:40px;line-height:40px;">
				<p style="padding:0px 20px;">$row[user_name]<i class="fa fa-angle-right" style="font-size:25px;color:#fff;float:right;margin-top:8px;"></i></p>
			</a>
EOC;
	
}


}
?>

			</div>
		</div>
		
<?php

$count=0;
if($main_s_sw==0){
	//タグ検索
	
	while ($m_s_r = mysql_fetch_assoc($main_tag_sound_r)) {
	
	$top_sound_r = mysql_query("SELECT * from soundways_sound WHERE sound_id='".$m_s_r["sound_id"]."'");
	while ($row = mysql_fetch_assoc($top_sound_r)) {
		
		$u_r = mysql_query("SELECT * from soundways_user_info WHERE user_id='".$row["user_id"]."'");
		while ($row2 = mysql_fetch_assoc($u_r)) {
			$user_name=$row2["user_name"];
			$user_id=$row2["user_id"];
		}
		
		$count++;
		$sound_img=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row["sound_img"]).".jpg";
		
print<<<EOC
		
		<div class="music_area" style='background:;'>
			<div class='music_img_over'>
			<a href="detail.php?id=$row[sound_id]" style='background-image:url(music_img_thumb/$sound_img);display:block;background-size:100% 100%;' class="music_img">
			<img src="img_icon/play_button.png" class="start_music_img">
			</a>
			</div>
			<div class="text_area">
				<p class="title" style='padding-top:3px;'><a href="detail.php?id=$row[sound_id]">$row[sound_title]</a></p>
				<p class="user_name"><a href="creator.php?id=$user_id">$user_name</a></p>
			</div>
		</div>
EOC;
			
		
		if($count%5==0){
			//echo "<div class='clear-fix'></div>";
		}
		
	}
	}


}else if($main_s_sw==1 && $main_u_sw==1){
	//普通の検索

	$s_r = mysql_query("SELECT * from soundways_sound ORDER BY sound_id DESC LIMIT 0,15");
	while ($row = mysql_fetch_assoc($s_r)) {
	
		$u_r = mysql_query("SELECT * from soundways_user_info WHERE user_id='".$row["user_id"]."'");
		while ($row2 = mysql_fetch_assoc($u_r)) {
			$user_name=$row2["user_name"];
			$user_id=$row2["user_id"];
		}
		
		$count++;
		$sound_img=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row["sound_img"]).".jpg";
		
print<<<EOC
		
		<div class="music_area" style='background:;'>
			
			<div class='music_img_over'>
			<a href="detail.php?id=$row[sound_id]" style='background-image:url(music_img_thumb/$sound_img);display:block;background-size:100% 100%;' class="music_img">
				<img src="img_icon/play_button.png" class="start_music_img">
			</a>
			</div>
			<div class="text_area">
				<p class="title" style='padding-top:3px;'><a href="detail.php?id=$row[sound_id]">$row[sound_title]</a></p>
				<p class="user_name"><a href="creator.php?id=$user_id">$user_name</a></p>
			</div>
		</div>
EOC;
			
		
		if($count%5==0){
			//echo "<div class='clear-fix'></div>";
		}
		
	}

}else if($main_u_sw==0){


	$s_r = mysql_query("SELECT * from soundways_sound WHERE user_id='".$main_u."' ORDER BY sound_id DESC LIMIT 0,15");
	while ($row = mysql_fetch_assoc($s_r)) {
	
		$u_r = mysql_query("SELECT * from soundways_user_info WHERE user_id='".$row["user_id"]."'");
		while ($row2 = mysql_fetch_assoc($u_r)) {
			$user_name=$row2["user_name"];
			$user_id=$row2["user_id"];
		}
		
		$count++;
		$sound_img=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row["sound_img"]).".jpg";
		
print<<<EOC
		
		<div class="music_area" style='background:;'>
			<div class='music_img_over'>
			<a href="detail.php?id=$row[sound_id]" style='background-image:url(music_img_thumb/$sound_img);display:block;background-size:100% 100%;' class="music_img">
			<img src="img_icon/play_button.png" class="start_music_img">
			</a>
			</div>
			<div class="text_area">
				<p class="title" style='padding-top:3px;'><a href="detail.php?id=$row[sound_id]">$row[sound_title]</a></p>
				<p class="user_name"><a href="creator.php?id=$user_id">$user_name</a></p>
			</div>
		</div>
EOC;
			
		
		if($count%5==0){
			//echo "<div class='clear-fix'></div>";
		}
		
	}


	
}

?>
		<div class="clear-fix"></div>
	</div>
</div>
<?php
include("footer.php");
?>
</div>
<!-- コメント -->
</body>
</html>
<?php
mysql_close($link);
?>
