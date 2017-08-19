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
	$user_id=1;
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


$result = mysql_query("SELECT * from soundways_sound WHERE sound_id = '".$sound_id."'");
while ($row = mysql_fetch_assoc($result)) {
	$sound_title=$row['sound_title'];
	$sound_text=$row['sound_text'];
	//$sound_img=$row['sound_img'];
	$sound_img=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row["sound_img"]).".jpg";
	$sound_filename=$row['sound_filename'];
	$sound_plays=$row['sound_plays'];
	$sound_goods=$row['sound_goods'];
	$time=$row['time'];
	$sound_user_id=$row['user_id'];
	$sound_purchase_sw=$row['sound_purchase'];
	$sound_price=$row['sound_price'];
}

$result = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$sound_user_id."'");
while ($row = mysql_fetch_assoc($result)) {
	$sound_user_name=$row['user_name'];
	$sound_user_profile=$row['user_profile'];
	
	if(empty($row["user_img"])){
		$sound_user_img= "tmp_img.jpg";
	}else{
		$sound_user_img=$row['user_img'];
	}
	$sound_user_follow = $row['user_follower_num'];
	$sound_user_sounds = $row['user_sound_num'];
	$official=$row['official'];
}

//コメント一覧
$comment_r = mysql_query("SELECT * from soundways_comment WHERE sound_id = '".$sound_id."' ORDER BY comment_id DESC");
$commentnum_r = mysql_query("SELECT COUNT(comment_id) as comment_num from soundways_comment WHERE sound_id = '".$sound_id."'");
while ($row = mysql_fetch_assoc($commentnum_r)) {
	$comment_num=$row["comment_num"];
}

//おすすめサウンド一覧
$recommend_r = mysql_query("SELECT * from soundways_sound LIMIT 0,10");

//タグ一覧
$tag_r_box = mysql_query("SELECT * from soundways_sound_tag_box WHERE sound_id = '".$sound_id."' LIMIT 0,20");


//notification更新
$result = mysql_query("SELECT * from soundways_notification_box WHERE user_id = '".$user_id."' AND sound_id = '".$sound_id."'");
while ($row = mysql_fetch_assoc($result)) {
	//あった場合
	if($row["type"]==1 || $row["type"]==3){
		
		$sql = "DELETE FROM soundways_notification_box WHERE user_id = '".$user_id."' AND sound_id = '".$sound_id."'";
		$result_flag = mysql_query($sql);
		
		$result = mysql_query("SELECT COUNT(noti_id) as user_notification_num from soundways_notification_box WHERE user_id = '".$user_id."'");
		while ($row = mysql_fetch_assoc($result)) {
			$user_notification_num=$row['user_notification_num'];
		}
		
		$sql = "UPDATE soundways_user_info SET user_notification_num = '$user_notification_num' WHERE user_id = '".$user_id."'";
		$result_flag = mysql_query($sql);
		
	}
	
}


//フォローしているかどうか	
$follow_ok=0;
$result = mysql_query("SELECT * from soundways_following WHERE following_user_id = '".$user_id."' AND followed_user_id = '".$sound_user_id."'");
while ($row = mysql_fetch_assoc($result)) {
	$follow_ok=1;
}

//いいねしているかどうか
$good_ok=0;
$result = mysql_query("SELECT goods_id from soundways_goods_box WHERE user_id = '".$user_id."' AND sound_id = '".$sound_id."'");
while ($row = mysql_fetch_assoc($result)) {
	$goods_id=$row['goods_id'];
	$good_ok=1;
}

//通知データベース
$soundways_top_activity_r = mysql_query("SELECT * from soundways_activity WHERE user_id = '".$user_id."' ORDER BY activity_id DESC LIMIT 0,4");


//購入しているかどうか
$sound_purchase_ok==0;


?><!DOCTYPE html>
<html lang="ja"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $sound_title;?> - SoundWays</title>
<meta name="keywords" content="立体音響,<?php echo $sound_title;?>,<?php echo $sound_text;?>,<?php echo $sound_user_name;?>" />
<meta name="description" content="立体音響 - <?php echo $sound_title;?> 、<?php echo $sound_text;?>" />
<meta name="viewport" content="width=device-width">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<link href="" rel="shortcut icon" type="image/x-icon">
<link rel='stylesheet' type='text/css' href='design.css'>
<script src="js/jquery-2.0.2.min.js" type="text/javascript"></script>
<script src="js/top_bar.js" type="text/javascript"></script>
<script src="js/ajax_func.js" type="text/javascript"></script>
<link rel="stylesheet" href="music_player/clarity_player/example/clarity/css/style.css"/>
<link rel="stylesheet" href="music_player/clarity_player/example/styles.css"/>
<?php
include("views_parts/all_head.php");
?>


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





$(".comment_submit").click(function(){
comment_sound_id = $(".comment_form .comment_sound_id").val();
comment_text = $(".comment_form .comment_text").val();
comment_sound_id=comment_sound_id.replace(/"/g,"");
comment_sound_id=comment_sound_id.replace(/'/g,"");
comment_text=comment_text.replace(/"/g,"");
comment_text=comment_text.replace(/'/g,"");

	$.ajax({
		url: 'cgi/ajax_comment.php',
		type: 'POST',
		data: "sound_id="+comment_sound_id+"&text="+comment_text,
		success: function(data) {
			data= JSON.parse(data);
			if(data.error_num==3){
				alert("ログインされていません。");
			}else{
				location.href="detail.php?id=<?php echo $sound_id;?>";
			}

		}
	});



	
	//$(".comment_form").submit();
});	

reply_on_num = -1;
//コメント返信
$(".reply_comment").click(function(){

	if(reply_on_num != -1){
		$(".comment_reply_main_area"+reply_on_num).remove();		
	}
	
	reply_on_num=$(this).attr("id");
	reply_user_name=$(".reply_user_name_"+$(this).attr("id")).val();
	
	re_tmp_str="<div class='comment_reply_main_area"+$(this).attr("id")+"' style='background:;width:100%;height:80px;padding:10px 0px;'><textarea class='comment_reply_text' name='text' placeholder='"+reply_user_name+"さんに返信する' style='color:#1a1a1a;padding-left:10px;font-size:16px;width:100%;border:1px solid silver;height:80px;'></textarea><div class='comment_reply_submit' style='float:right;width:180px;border-radius:5px;background:#ff5a5f;color:white;height:30px;line-height:30px;border:none;text-align:center;cursor:pointer;'>返信する</div><div class='clear-fix'></div></div><div class='clear-fix'></div>";

	$(".reply_area"+$(this).attr("id")).after(re_tmp_str);




/*	$.ajax({
		url: 'cgi/ajax_reply_comment.php',
		type: 'POST',
		data: "sound_id=<?php echo $sound_id;?>&comment_id="+$(this).attr("id"),
		success: function(data) {
			alert(data);
			//data= JSON.parse(data);
		}
	});
*/

	
});


$(document).on('click',".comment_reply_submit",function(){

	$.ajax({
		url: 'cgi/ajax_sound_comment_reply.php',
		type: 'POST',
		data: "comment_id="+reply_on_num+"&text="+$(".comment_reply_text").val(),
		success: function(data) {
		   data = JSON.parse(data);
		   	if(data.error_num==3){
				alert("ログインされていません。");
			}else{
				location.href="detail.php?id=<?php echo $sound_id;?>";
			}
		}
	});
	
	
});

//いいね


$(".good_button").click(function(){

	$.ajax({
		url: 'cgi/ajax_sound_good.php',
		type: 'POST',
		data: "sound_id=<?php echo $sound_id;?>",
		success: function(data) {
alert(data);

		data= JSON.parse(data);
			if(data.error_num==1){
				if(data.goods_flg==0){
					//いいね-1
					//$(".good_button_str").text("いいね済み");
					$(".good_button").css({"background":"#ff5a5f","color":"#fff","border":"0","padding":"5px 15px"});
					$(".good_button_icon").css({"color":"#fff"});
					
					$(".sound_good_num").text(data.goods_num);
					good_js_sw=1;
				}else{
					//いいね+1
					//$(".good_button_str").text("いいね");
					$(".good_button").css({"background":"#fff","border":"1px solid #e5e5e5","color":"#333","padding":"4px 14px"});
					$(".good_button_icon").css({"color":"#333"});
					$(".sound_good_num").text(data.goods_num);
					good_js_sw=0;
				}
				
			}
		}
	});
	
});

$(".good_button").hover(function(){
	
	if(good_js_sw==0){
		$(this).css({"background":"#fff","color":"#ff5a5f","border":"1px solid #ff5a5f","padding":"4px 14px"});
		$(this).children(".good_button_icon").css({"color":"#ff5a5f"});
	}
	
},function(){
	if(good_js_sw==0){
		$(this).css({"background":"#fff","border":"1px solid #e5e5e5","color":"#333","padding":"4px 14px"});
		$(this).children(".good_button_icon").css({"color":"#333"});
	}
});


//

<?php echo "follow_js_sw=".$follow_ok.";";?>

$(".follow_button").click(function(){

	$.ajax({
		url: 'cgi/ajax_follow_button.php',
		type: 'POST',
		data: "creator_user_id=<?php echo $sound_user_id;?>",
		success: function(data) {
		data= JSON.parse(data);
			if(data.error_num==1){
				if(data.flg==1){
					//フォロー+1
					$(".follow_button").css({"background":"#59c146","color":"#fff","border":"0","padding":"5px 15px"});
					$(".follow_button_icon").css({"color":"#fff"});
					$(".follow_button_str").text("フォロー");
					
					$(".user_follower_num").text(data.follower_num);
					follow_js_sw=1;
				}else{
					//フォロー-1
					$(".follow_button").css({"background":"#fff","border":"1px solid #e5e5e5","color":"#333","padding":"4px 14px"});
					$(".follow_button_icon").css({"color":"#333"});
					$(".follow_button_str").text("フォロー");
					$(".user_follower_num").text(data.follower_num);
					follow_js_sw=0;
				}
				
			}
		}
	});
	
});

$(".follow_button").hover(function(){
	if(follow_js_sw==0){
		$(this).css({"background":"#59c146","color":"#fff","border":"0","padding":"5px 15px"});
		$(this).children(".follow_button_icon").css({"color":"#fff"});
// 		$(this).children(".follow_button_str").text("フォロー済み");
	}
	
},function(){
	if(follow_js_sw==0){
		$(this).css({"background":"#fff","border":"1px solid #e5e5e5","color":"#333","padding":"4px 14px"});
		$(this).children(".follow_button_icon").css({"color":"#333"});
// 		$(this).children(".follow_button_str").text("フォローする");
	}
});



$(document).on('click',".play",function(){

	$.ajax({
		url: 'cgi/ajax_sound_play.php',
		type: 'POST',
		data: "sound_id=<?php echo $sound_id;?>",
		success: function(data) {
		   //upload = JSON.parse(data);
		}
	});

});

$(document).on('click',".previous",function(){

	$.ajax({
		url: 'cgi/ajax_sound_play.php',
		type: 'POST',
		data: "sound_id=<?php echo $sound_id;?>",
		success: function(data) {
		   //upload = JSON.parse(data);
		}
	});
	
});

$(document).on('click',".next",function(){

	$.ajax({
		url: 'cgi/ajax_sound_play.php',
		type: 'POST',
		data: "sound_id=<?php echo $sound_id;?>",
		success: function(data) {
		   //upload = JSON.parse(data);
		}
	});
	
});


});
</script>
</head>
<!------------------------------------------ body ----------------------------------------->
<body>

<div id="example-wrapper">

    <div id="example-outer">
        <div id="example">
        <div style="font-size:30px;color:white;text-shadow:0px 0px 1px #333;background:;position:absolute;width:50%;height:100px;z-index:22;margin-left:45px;padding-top:80px;">
	       <p style="line-height:50px;"><?php echo $sound_title;?></p>
	       <p style="font-size:22px;"><?php echo $sound_user_name;?></p>
        </div>
        </div>

    </div>
    <div class="clear-fix"></div>
</div>
<div id="page">

<!-- -->
<?php
include("top_bar.php");
?>


<!-- -->
<div class="under_bar"></div>

	<div style="width:1200px;background:;height:70px;margin:auto;font-size:22px;color:#444;font-weight:100;border-bottom:1px solid #eaeaea;">

		<div style="float:left;width:60%;padding-top:15px;font-size:20px;">
<?php
if($good_ok){
print<<<EOC
			<div class="good_button" style="font-size:18px;cursor:pointer;float:left;border-radius:3px;background:#ff5a5f;color:#fff;padding:5px 15px;"><i class="fa fa-heart good_button_icon"></i> <span class="good_button_str">お気に入り</span></div>
EOC;
}else{
print<<<EOC
			<div class="good_button" style="font-size:18px;cursor:pointer;float:left;border-radius:3px;border:1px solid #e5e5e5;color:#333;padding:4px 14px;"><i class="fa fa-heart good_button_icon" style='color:#333;'></i> <span class="good_button_str">お気に入り</span></div>
EOC;
}
?>

<?php
if($follow_ok){
	
print<<<EOC
	<div class='follow_button' style='margin-left:10px;font-size:18px;border-radius:3px;cursor:pointer;float:left;background:#59c146;color:#fff;padding:5px 15px;'>
		<i class="fa fa-plus"></i>
		<span class='follow_button_str'>フォロー</span>
	</div>
EOC;

}else{

print<<<EOC
	<div class='follow_button' style='margin-left:10px;font-size:18px;border-radius:3px;cursor:pointer;float:left;border:1px solid #e5e5e5;background:#fff;color:#333;padding:4px 14px;'>
		<i class="fa fa-plus"></i>
		<span class='follow_button_str'>フォロー</span>
	</div>
EOC;

}
	
?>
	
	

			<div class="share_button" style="display:none;margin-left:10px;font-size:18px;cursor:pointer;float:left;background:#22a;color:#fff;padding:5px 15px;"><i class="fa fa-share-alt"></i> <span class="share_button_str">シェアする</span></div>

<?php

if($sound_purchase_sw==1){

if($sound_purchase_ok==0){
print<<<EOC
			<div style="cursor:pointer;float:left;border:2px solid #59c146;color:#59c146;padding:2px 15px;margin-left:10px;"><i class="fa fa-credit-card"></i> 購入する(?{$sound_price})</div>
EOC;
}else{
print<<<EOC
			<div style="cursor:pointer;float:left;border:2px solid #59c146;color:#59c146;padding:2px 15px;margin-left:10px;"><i class="fa fa-credit-card"></i> 購入済み(?{$sound_price})</div>
EOC;
}

}
?>

		</div>
	
		<div style="float:right;width:40%;line-height:70px;text-align:right;">
			<span><i class="fa fa-play"></i> <?php echo $sound_plays;?></span>
			<span style="margin-left:20px;"><i class="fa fa-heart"></i> <span class="sound_good_num"><?php echo $sound_goods;?></span></span>
			
		</div>
		<div class="clear-fix"></div>
	</div>
	
	<div style="width:1200px;margin:auto;padding-top:30px;">
	
		<div style="float:left;width:700px;background:;">
			<div style="border-bottom:1px solid #eaeaea;padding-bottom:30px;">
				<?php echo $sound_text;?>
			</div>
			<div style="padding:20px 0px;border-bottom:1px solid #eaeaea;">
				<img src="userimg/<?php echo $sound_user_img;?>" style="float:left;width:120px;height:120px;">
				<div style="float:left;padding:0px 0px 0px 10px;color:#888;">
					<h3>
						<a href="creator.php?id=<?php echo $sound_user_id;?>" style="color:#111;font-size:20px;">
						<?php echo $sound_user_name;?>
						</a>
					</h3>
					<p>
						<span class="user_follower_num"><?php echo $sound_user_follow;?></span> フォロワー
					</p>
					<p>
						<?php echo $sound_user_profile;?>
					</p>
				</div>
				<div class="clear-fix"></div>
			</div>
			
			<div style="padding:20px 0px;border-bottom:1px solid #eaeaea;">
			<span style="font-size:20px;color:#333;font-weight:bold;margin-right:20px;">タグ</span>
			
<?php
$tag_count=0;
while ($row_t = mysql_fetch_assoc($tag_r_box)) {

	//タグ一覧
	$tag_r = mysql_query("SELECT tag_text from soundways_sound_tag WHERE tag_id='".$row_t["tag_id"]."'");
	
	while ($row2 = mysql_fetch_assoc($tag_r)) {
		echo "<a href='search.php?s=%23$row2[tag_text]' class='details_tags' style='margin-right:10px;'>#$row2[tag_text]</a>";
	}
	
	$tag_count++;
}

?>
			</div>
		
		
			<!-- コメントエリア -->
			<div style="padding:20px 0px;">
				<span style="font-size:20px;color:#333;font-weight:bold;">コメント - <?php echo $comment_num;?>件</span>
<?php

print<<<EOC
				<div class="comment_form" style="padding:40px 0px;border-bottom:1px solid #eaeaea;">
					<input type="hidden" class="comment_sound_id" name="sound_id" value="$sound_id">
					<div style="width:100px;float:left;margin-left:10px;text-align:center;">
						<img src="userimg/$user_img" width="80px" height="80px" style="border-radius:50px;">
					</div>
					
					<div style="width:570px;float:left;margin-left:20px;">
						<textarea class="comment_text" name="text" placeholder="聴いた感想を伝えてみましょう" style="color:#1a1a1a;padding-left:10px;font-size:16px;width:100%;border:1px solid silver;height:80px;" required></textarea>
						
						<div class="comment_submit" style="float:right;width:180px;border-radius:3px;background:#ff5a5f;color:white;height:30px;line-height:30px;border:none;text-align:center;cursor:pointer;margin-top:5px;">
							コメントする
						</div>
				
					</div>
EOC;
?>		
					<div class="clear-fix"></div>
				</div>
				
			


<div style="margin-top:0px;">
<?php

while ($row = mysql_fetch_assoc($comment_r)) {

$now_time = date("Y-n-d H:i:s");
$time=date('Y-n-d H:i:s',strtotime($row["time"]));

$diff_day = (strtotime($now_time)-strtotime($time));

if($diff_day>=0 && $diff_day<60){
	$diff_day_str = $diff_day."秒前";
}else if($diff_day>=60 && $diff_day<3600){
	$diff_day = floor($diff_day/60);
	$diff_day_str = $diff_day."分前";
}else if($diff_day>=3600 && $diff_day<86400){
	$diff_day = floor($diff_day/3600);
	$diff_day_str = $diff_day."時間前";
}else if($diff_day>=86400){
	$diff_day = floor($diff_day/86400);
	$diff_day_str = $diff_day."日前";
}



$text = $row["comment"];
$comment_id=$row["comment_id"];
$c_u_r = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$row["user_id"]."'");
while ($row2 = mysql_fetch_assoc($c_u_r)) {
	$comment_user_name = $row2["user_name"];
	$comment_user_id = $row2["user_id"];
	if(empty($row2["user_img"])){
		$comment_user_img= "tmp_img.jpg";
	}else{
		$comment_user_img = $row2["user_img"];
	}
	//$sound_img=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row["sound_img"]).".jpg";
}
print <<<EOC

<div style="padding:20px 0px;border-bottom:1px solid #eaeaea;">

	
	<a href='creator.php?id=$comment_user_id' style="display:block;width:100px;float:left;margin-left:10px;text-align:center;">
		<img src="userimg/$comment_user_img" width="80px" height="80px" style="border-radius:50px;">
	</a>
	<div style="width:590px;padding-left:20px;float:left;background:;">
		<a href='creator.php?id=$comment_user_id' style="font-size:18px;color:#ff5a5f;">$comment_user_name</a>
		<p style="font-size:16px;padding-top:3px;">$text</p>
		<p class="reply_area{$comment_id}" style="font-size:13px;padding-top:8px;color:gray;">
			<i class="fa fa-clock-o" style='font-size:16px;'></i>
			<span style='margin:0px 5px 0px 0px;'>$diff_day_str</span>
			<span class='reply_comment' id='$comment_id' style='color:#888;cursor:pointer;'><i class="fa fa-reply"></i> 返信</a>
			<input class='reply_user_name_{$comment_id}' type='hidden' value='$comment_user_name'>
		</p>
	</div>
	<div class="clear-fix"></div>
EOC;

$reply_result = mysql_query("SELECT * from soundways_comment_reply WHERE comment_id = '".$comment_id."' ORDER BY reply_id ASC");
while ($reply_row = mysql_fetch_assoc($reply_result)) {

$reply_c_u_r = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$reply_row["user_id"]."'");
while ($reply_row2 = mysql_fetch_assoc($reply_c_u_r)) {
	$reply_user_name = $reply_row2["user_name"];
	$reply_user_id = $reply_row2["user_id"];
	
	if(empty($reply_row2["user_img"])){
		$reply_user_img= "tmp_img.jpg";
	}else{
		$reply_user_img = $reply_row2["user_img"];
	}
}

$time_r=date('Y-n-d H:i:s',strtotime($reply_row["time"]));

$diff_day_r = (strtotime($now_time)-strtotime($time_r));

if($diff_day_r>=0 && $diff_day_r<60){
	$diff_day_str_r = $diff_day_r."秒前";
}else if($diff_day_r>=60 && $diff_day_r<3600){
	$diff_day_r = floor($diff_day_r/60);
	$diff_day_str_r = $diff_day_r."分前";
}else if($diff_day_r>=3600 && $diff_day_r<86400){
	$diff_day_r = floor($diff_day_r/3600);
	$diff_day_str_r = $diff_day_r."時間前";
}else if($diff_day_r>=86400){
	$diff_day_r = floor($diff_day_r/86400);
	$diff_day_str_r = $diff_day_r."日前";
}


print <<<EOC

<div style='width:100%;background:;margin:20px 0px;margin-left:50px;'>

	<a href='creator.php?id=$reply_user_id' style="display:block;width:100px;float:left;margin-left:8px;text-align:center;border-left:1px solid #ddd;">
		<img src="userimg/$reply_user_img" width="80px" height="80px" style="border-radius:50px;">
	</a>
	<div style="width:540px;padding-left:10px;float:left;background:;">
		<a href='creator.php?id=$reply_user_id' style="font-size:18px;color:#ff5a5f;">$reply_user_name</a>
		<p style="font-size:16px;padding-top:3px;">{$reply_row[text]}</p>
		<p class="" style="font-size:13px;padding-top:8px;color:gray;">
			<i class="fa fa-clock-o" style='font-size:16px;'></i>
			<span style='margin:0px 5px 0px 0px;'>{$diff_day_str_r}</span>
		</p>
	</div>
	<div class="clear-fix"></div>
</div>
EOC;
}



print<<<EOC

</div>

EOC;

}
	
?>				

</div>
			</div>
			

		</div>
		
		<div style="float:right;width:460px;">
		
<?php

while ($row = mysql_fetch_assoc($recommend_r)) {

$sound_img_r=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row["sound_img"]).".jpg";

$c_u_r = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$row["user_id"]."'");
while ($row2 = mysql_fetch_assoc($c_u_r)) {
	$recommend_user_name = $row2["user_name"];
	$recommend_user_id = $row2["user_id"];
}
print <<<EOC

<div style="margin-bottom:20px;height:150px;background:white;box-shadow:0px 0px 1px 0px #ccc;">
	<a href='detail.php?id=$row[sound_id]' style="display:block;width:150px;float:left;">
		<img src="music_img_thumb/$sound_img_r" width="150px" height="150px" style='border-right:1px solid #dedede;'>
	</a>
	<div style='float:left;padding:8px 10px;'>
		<a href='detail.php?id=$row[sound_id]' style="color:#333;display:block;font-size:20px;line-height:25px;">$row[sound_title]</a>
		<p style="margin:5px 0px;"><a href='creator.php?id=$recommend_user_id' style="color:gray;font-size:16px;">$recommend_user_name</a></p>
		<p style="font-size:16px;color:#888;">再生回数 $row[sound_plays]回</p>
	</div>
	<div class="clear-fix"></div>
</div>

EOC;

}
	
?>			

			
		</div>
		<div class="clear-fix"></div>
	</div>
	

<?php
include("footer.php");
?>

<!-------------- page end -------------->
</div>
<!-- コメント -->
<script src="music_player/clarity_player/example/clarity/ttw-clarity-player.js"></script>
<script src="music_player/clarity_player/example/clarity/yepnope.js"></script>
<script type="text/javascript">
    $(function () {
        var myPlaylist = [
            {
                mp3:'music/<?php echo $sound_filename; ?>',
                duration:'1:25',
                cover:'music_img_thumb/<?php echo $sound_img;?>',
                title:"<?php echo $sound_title; ?>",
                artist:"<?php echo $sound_user_name; ?>",
                background:'music_img_thumb/<?php echo $sound_img; ?>'
            }/*
,
            {
                mp3:'music_player/clarity_player/example/playlist/ODESZA_f_Madelyn_Grant-Sun_Models.mp3',
                duration:'1:25',
                cover:'music_player/playlist/covers/odesza.jpg',
                title:'Sun Models',
                artist:'ODESZA f. Madelyn Grant',
                background:'music_player/clarity_player/example/assets/site-mix/bgs/bg5.jpg'
            },
            {
                mp3:'music_player/clarity_player/example/playlist/BANKS-BRAIN_TA-KU_REMIX.mp3',
                duration:'1:25',
                cover:'music_player/clarity_player/example/playlist/covers/banks.jpg',
                title:'Brain (Ta-Ku Remix)',
                artist:'BANKS',
                background:'music_player/clarity_player/example/assets/site-mix/bgs/bg6.jpg'
            },

            {
                mp3:'music_player/clarity_player/example/playlist/ASTR-Part_Of_Me.mp3',
                duration:'1:25',
                cover:'music_player/clarity_player/example/playlist/covers/astr.jpg',
                title:'Part Of Me',
                artist:'ASTR',
                background:'music_player/clarity_player/example/assets/site-mix/bgs/bg7.jpg'
            }
*/
        ];

        var clarity = $('#example').ttwClarityPlayer(myPlaylist);


        $('#example-size-picker').on('click', 'li', function(){
            $('#example').attr('data-size', $(this).data('size'));
            clarity.manageLayout();

        });
    });
</script>
</body>
</html>
<?php
mysql_close($link);
?>