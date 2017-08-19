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


$top_sound_r = mysql_query("SELECT * from soundways_sound ORDER BY sound_id DESC LIMIT 0,18");


$top_tag_sound_r = mysql_query("SELECT * from soundways_sound_tag_box WHERE tag_id='23' ORDER BY sound_id DESC LIMIT 0,6");




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


/*
	
	var gameheight = $(window).height();
	var undergameheight=0;
	if(gameheight >= 720){
		undergameheight=720;
		$("#sound_ways_area").css("height","720px");
	}else if(gameheight < 720 && gameheight >= 505){
		undergameheight=gameheight;
		$("#sound_ways_area").css("height",gameheight+"px");
	}else{
		undergameheight=720;
		$("#sound_ways_area").css("height","720px");
	}
*/


	$(".under_go_btn").click(function(){
		$("body").animate({scrollTop:undergameheight-60});
	});
	

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
			"top":"-60px"
		},250);
		
		$(".top_bar").css("box-shadow","none");		
		$(".user_icon_menu").fadeOut();	
	}
	
});

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

<div id="sound_ways_area" style="background-img:url('img/soundwaysback.jpg');">
		
<!--
	<div class="under_go_btn">
		<img src="img_icon/soundways_top_down_button.png" style="width:70px;">
	</div>
	
-->
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
	
	
	<div style="padding-top:100px;">
	<div style="text-align:center;">
		<img src="img/soundways_about_text.png" style="width:600px;">
	</div>
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