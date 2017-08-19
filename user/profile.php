<?php
session_start();
include("../db.php");
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
	$user_img=$row['user_img'];
	$login_ok=1;
}

if($login_ok==1){

}else{
	header("Location: ../login.php");	
}




$creator_id=$user_id;
$result = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$creator_id."'");
while ($row = mysql_fetch_assoc($result)) {
	$creator_name=$row['user_name'];

	$user_notification_num=$row['user_notification_num'];

	$creator_url=$row['user_url'];
	$creator_profile=$row['user_profile'];
	$creator_location=$row['user_location'];

$creator_profile = str_replace("<br>", "
", $creator_profile);

	
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



$sound_r = mysql_query("SELECT * from soundways_sound WHERE user_id = '".$creator_id."' ORDER BY sound_id DESC");
	
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
<script src="../js/jquery-2.0.2.min.js" type="text/javascript"></script>
<script src="../js/top_bar.js" type="text/javascript"></script>

<script type="text/javascript">
//jQuery
$(document).ready(function(){
//点滅




	//ユーザーイメージ
	$('.user_img_update_btn').click(function() {
		// ダミーボタンとinput[type="file"]を連動
		$('.user_img_btn').click();
	});

	$('.user_img_btn').change(function(){
		// ファイル情報を取得
		var files = this.files;
		uploadFiles(files,1);
	});
	
	//ヘッダーイメージ
	$('.header_img_update_btn').click(function() {
		// ダミーボタンとinput[type="file"]を連動
		$('.header_img_btn').click();
	});

	$('.header_img_btn').change(function(){
		// ファイル情報を取得
		var files = this.files;
		uploadFiles(files,2);
	});
	


		
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


	
function uploadFiles(files,type) {
	// FormDataオブジェクトを用意
	var fd = new FormData();
	var uploadsw=0;
	// ファイルの個数を取得
	var filesLength = files.length;
	
	// ファイル情報を追加
	for (var i = 0; i < filesLength; i++) {
	fd.append("upfile", files[i]);
	}
	fd.append("type", type);
if(type==1){

	// Ajaxでアップロード処理をするファイルへ内容渡す
	$.ajax({
		url: 'cgi/ajax_upload_user_img.php',
		type: 'POST',
		data: fd,
		processData: false,
		contentType: false,
		success: function(data) {
		upload = JSON.parse(data);
		var timestamp = new Date().getTime();
		var tmp_str = "tmp_user_img/"+upload.filename+'?'+timestamp;
		$(".tmp_user_img_file").attr({value:upload.filename});
		$(".user_img_area").attr({src:tmp_str});
		}
	});


}else if(type==2){

	// Ajaxでアップロード処理をするファイルへ内容渡す
	$.ajax({
		url: 'cgi/ajax_upload_header_img.php',
		type: 'POST',
		data: fd,
		processData: false,
		contentType: false,
		success: function(data) {
		upload = JSON.parse(data);
		var timestamp = new Date().getTime();
		var tmp_str = "tmp_user_img/"+upload.filename+'?'+timestamp;
		$(".tmp_header_img_file").attr({value:upload.filename});
		$(".header_img_area").css("background-image","url("+tmp_str+")");
		}
	});
	
}

}



</script>
</head>
<!------------------------------------------ body ----------------------------------------->
<body>
<div id="page">

<!-- -->
<?php
include("header.php");
?>


<!-- -->
<div class="under_bar">
	
</div>
<form action="cgi/profile_edit.php" method="post">
<input type="hidden" value="" name="tmp_user_img_file" class="tmp_user_img_file">
<input type="hidden" value="" name="tmp_header_img_file" class="tmp_header_img_file">
<div id="creator_contents" class="header_img_area" style="padding-top:50px;background-image:url('../userimg/<?php echo $creator_header_img;?>');background-size:100% auto;width:100%;height:380px;">
<div style="background-image:url('../img/modal_back2.png');height:380px;">

	<div class="header_img_update_btn" style="cursor:pointer;margin-top:100px;margin-left:40px;border:4px solid white;text-align:center;color:white;font-size:20px;width:260px;height:150px;line-height:150px;position:absolute;text-shadow:0px 1px 1px #666;">
	ヘッダー画像を変更する
	</div>
	<input type="file" class="header_img_btn" name="upfile" multiple="multiple" style="display:none;">

	<div style="display:none;position:absolute;right:30px;top:80px;">
		<a style="display:block;float:left;width:30px;margin-right:15px;"><img src="../img_icon/creator_n_icon.png" style="width:30px;"></a>
		<a style="display:block;float:left;width:30px;margin-right:15px;"><img src="../img_icon/creator_t_icon.png" style="width:30px;"></a>
		<a style="display:block;float:left;width:30px;"><img src="../img_icon/creator_f_icon.png" style="width:30px;"></a>
		<div class="clear-fix"></div>
	</div>
	<div style="text-align:center;padding-top:30px;">
		<div style="position:relative;margin:auto;width:150px;">
			<input type="file" class="user_img_btn" name="upfile" multiple="multiple" style="display:none;">
			<div class="user_img_update_btn" style="cursor:pointer;width:100%;padding-top:55px;color:white;font-size:18px;position:absolute;text-shadow:0px 1px 1px #666;">ユーザー画像を<br>変更する</div>
			<img class="user_img_area" src="../userimg/<?php echo $creator_img;?>" style="cursor:pointer;border-radius:100px;width:150px;height:150px;">
		</div>
		
		<p style="color:white;font-size:25px;">
			<input type="text" name="name" placeholder="ユーザー名" style="opacity:0.98;height:30px;line-height:30px;padding-left:10px;font-size:18px;border-radius:5px;border:0;text-align: center;" value="<?php echo $creator_name;?>" autofocus required>
		</p>
		
		<p style="color:white;font-size:25px;padding-top:15px;background:;margin:auto;width:470px;">
			<input type="text" name="location" placeholder="場所" style="float:left;width:220px;opacity:0.98;height:30px;line-height:30px;padding-left:10px;font-size:18px;border-radius:5px;border:0;text-align: center;" value="<?php echo $creator_location;?>">
			<input type="text" name="url" placeholder="URL" style="float:right;width:220px;opacity:0.98;height:30px;line-height:30px;padding-left:10px;font-size:18px;border-radius:5px;border:0; text-align: center;" value="<?php echo $creator_url;?>">
			<div class="clear-fix"></div>
		</p>
		
		<p style="margin-top:15px;">
		<textarea name="profile" style="opacity:0.98;padding:5px 10px;height:60px;font-size:18px;width:450px;border-radius:5px;border:0;text-align: center;"><?php echo $creator_profile;?></textarea>
		</p>
	</div>
</div>
</div>

<div style="background:white;width:100%;height:50px;line-height:50px;">
	<div style="width:1200px;margin:auto;font-size:20px;">
		<a class="menu_hover" style="opacity:0.5;display:block;float:left;width:80px;text-align:center;color:#333;">ALL</a>
		<a class="menu_hover" style="opacity:0.5;color:#ff5a5f;display:block;float:left;width:120px;text-align:center;">Sounds</a>
		<a class="menu_hover" style="opacity:0.5;display:block;float:left;width:120px;text-align:center;color:#333;">Following</a>
		<a class="menu_hover" style="opacity:0.5;display:block;float:left;width:120px;text-align:center;color:#333;">Follower</a>
		<a class="menu_hover" style="opacity:0.5;display:block;float:left;width:80px;text-align:center;color:#333;">Likes</a>
		

<input type="submit" value="編集を完了する" style='border:0;font-size:14px;cursor:pointer;display:block;float:right;width:170px;margin-top:10px;background:#59c146;height:30px;line-height:28px;border-radius:3px;text-align:center;color:#fff;'>


	</div>
</div>



<div style="background:#f3f3f3;width:100%;opacity:0.5;">

	<div style="width:1200px;margin:auto;padding:20px 0px;">
	
<?php

while ($row = mysql_fetch_assoc($sound_r)) {

$sound_img=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row["sound_img"]).".jpg";
$time=date('Y年n月d日 H:i',strtotime($row["time"]));
print <<<EOC
		<div style="background:white;box-shadow:0px 0px 1px 0px #ddd;margin-bottom:20px;">
			<div style="width:200px;float:left;height:200px;">
				<img src="../music_img_thumb/$sound_img" style="width:200px;height:200px;box-shadow:0px 0px 1px 0px #ddd;">
			</div>
			<div style="width:900px;float:left;height:200px;">
				<p style="border-bottom:1px solid #ddd;height:40px;line-height:40px;font-size:20px;padding-left:20px;overflow:hidden;">
					<a style='color:#333;'>$row[sound_title]</a>
				</p>
				<div style="color:#666;height:85px;background:;font-size:16px;padding-left:20px;">
					<p style='max-height:65px;overflow:hidden;'>$row[sound_text]</p>
					<p style="color:#888;font-size:13px;padding-top:3px;">
						$time
					</p>
				</div>
				<div class='creator_sound_tags' style='border-bottom:1px solid #ddd;height:30px;padding:3px 0px 0px 20px;'>
EOC;

//タグ一覧
$tag_r = mysql_query("SELECT * from soundways_sound_tag_box WHERE sound_id = '".$row["sound_id"]."'");
while ($row2 = mysql_fetch_assoc($tag_r)) {
	//タグ一覧
	$tag_r2 = mysql_query("SELECT tag_text from soundways_sound_tag WHERE tag_id='".$row2["tag_id"]."'");
	
	while ($row3 = mysql_fetch_assoc($tag_r2)) {
		echo "<a class='details_tags' style='margin-right:10px;'>#$row3[tag_text]</a>";
	}

}

print <<<EOC
			</div>
			
			<div style='height:40px;line-height:40px;padding:0px 20px;'>
				<div style='float:left;font-size:16px;margin-right:20px;'>7,987,498,237 plays</div>
				<div style='float:left;font-size:16px;'>8,237 gooods</div>
				<span style='float:right;margin-top:5px;display:block;background:#ff5a5f;color:white;border-radius:20px;line-height:20px;padding:5px 50px;'>Likes</span>

				<div class="clear-fix"></div>
			</div>
			</div>
		<div class="clear-fix"></div>
		</div>
EOC;
}

?>

	
	</div>	
</div>
</form>


<!-------------- page end -------------->
</div>
<!-- コメント -->
</body>
</html>
<?php
mysql_close($link);
?>