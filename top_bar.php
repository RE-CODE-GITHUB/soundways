<?php
if(!empty($_GET["s"])){
	$s=$_GET["s"];
}else{
	$s="";
}

$s = htmlspecialchars($s, ENT_QUOTES);
$url = explode(".",$_SERVER["REQUEST_URI"], 2);

if($url[0]==="/"){
	$url[0] ="index";
}

$url = str_replace(">", "", $url);
$url = str_replace("<", "", $url);
$url = str_replace("'", "", $url);
$url = str_replace("\"", "", $url);
$url = str_replace("/", "", $url);
?>
<div class="top_bar">

	<a href="./"><h1 class="top_h1">SoundWays</h1></a>
	
	<div style="padding-top:5px;float:left;width:32%;">
		<form action="search.php" method="get" class="top_search_btn">
			<i class="fa fa-search top_submit" style='color:#ddd;font-size:16px;margin-right:5px;'></i>
			<input type="text" name="s" value="<?php echo $s; ?>" class="top_searchtext" placeholder="3D SOUNDを検索する" required>
		</form>
	</div>
<?php
if(empty($login_ok)){
	$login_ok=0;
}
if($login_ok){
print <<<EOC

<div class='user_icon' style='float:right;height:50px;cursor:pointer;background:;padding:0px 20px;'>
<img src='userimg/$user_img' alt='userimg' style='width:25px;height:25px;border-radius:50px;vertical-align: middle;margin-bottom:3px;'>
<span style='color:#fff;margin-left:5px;line-height:50px;'>$user_name</span>
</div>


EOC;
if(!empty($user_notification_num)){

print<<<EOC
<div class="top_menu_btn noti_icon" style='cursor:pointer;width:100px;padding-right:15px;border-right:1px solid #191c1c;position:relative;'>
<div style='border-radius:30px;width:20px;height:20px;line-height:20px;font-size:12px;background:#f00;color:white;position:absolute;margin-top:15px;right:10px;'>{$user_notification_num}</div>
EOC;

}else{

print<<<EOC
<div class="top_menu_btn noti_icon" style='cursor:pointer;width:100px;border-right:1px solid #191c1c;position:relative;'>

EOC;

}

print<<<EOC
<div class='notification_menu'>
<img src='img_icon/noti_yajirushi.png' style='position:absolute;margin-top:-9px;margin-left:29%;'>
<div style='text-align:center;color:#333;line-height:24px;padding:10px 0px 5px 0px;border-bottom:1px solid #eee;'>通知</div>
<div style=''>
EOC;

$noti_count=0;
while ($row_noti = mysql_fetch_assoc($soundways_top_activity_r)) {

	$noti_count++;
	$noti_user_id=$row_noti['second_user_id'];
	
	
	if($row_noti['type'] == 1){
			
		$result_noti_u = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$noti_user_id."'");
		while ($row_n_u = mysql_fetch_assoc($result_noti_u)) {
			
			$noti_user_name=$row_n_u['user_name'];
		
			if(empty($row_n_u["user_img"])){
				$noti_user_img = "tmp_img.jpg";
			}else{
				$noti_user_img = $row_n_u["user_img"];
			}
		}
		
		$result_s_sound = mysql_query("SELECT * from soundways_sound WHERE sound_id = '".$row_noti["sound_id"]."'");
		while ($row3 = mysql_fetch_assoc($result_s_sound)) {
			$activity_sound_title = $row3["sound_title"];
		}
		
		$noti_str = $noti_user_name."さんが".$activity_sound_title."にコメントしました。";
		
		//リンク
		$noti_link_url="detail.php?id=".$row_noti["sound_id"];
		
		
	}else if($row_noti['type'] == 2){
		
		
		$result_s_u = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$noti_user_id."'");
		while ($row3 = mysql_fetch_assoc($result_s_u)) {
			$activity_user_name=$row3["user_name"];
			//$activity_img = "../userimg/".$row3["user_img"];
		}
		
		$result_s_sound = mysql_query("SELECT * from soundways_sound WHERE sound_id = '".$row_noti["sound_id"]."'");
		while ($row3 = mysql_fetch_assoc($result_s_sound)) {
			$activity_sound_title = $row3["sound_title"];
			$noti_sound_img = "../music_img_thumb/".preg_replace("/(.+)(\.[^.]+$)/", "$1",$row3["sound_img"]).".jpg";
		}
		
		$noti_str = $activity_sound_title."をアップロードしました。";
		
		//リンク
		$noti_link_url="detail.php?id=".$row_noti["sound_id"];
		
	}else if($row_noti['type'] == 3){
		//返信の通知
		
		$result_s_u = mysql_query("SELECT * from soundways_user_info WHERE user_id = '".$noti_user_id."'");
		while ($row3 = mysql_fetch_assoc($result_s_u)) {
			$activity_user_name=$row3["user_name"];
			if(empty($row3["user_img"])){
				$noti_user_img  = "../userimg/tmp_img.jpg";
			}else{
				$noti_user_img = "../userimg/".$row3["user_img"];
			}
		}
		
		$result_s_sound = mysql_query("SELECT * from soundways_sound WHERE sound_id = '".$row_noti["sound_id"]."'");
		while ($row3 = mysql_fetch_assoc($result_s_sound)) {
			$activity_sound_title = $row3["sound_title"];
		}
		
		$result_s_comment = mysql_query("SELECT * from soundways_comment WHERE comment_id = '".$row_noti["comment_id"]."'");
		while ($row3 = mysql_fetch_assoc($result_s_comment)) {
			$activity_sound_comment = $row3["comment"];
			
			$activity_sound_comment = str_replace("<br>", "", $activity_sound_comment);
			$activity_sound_comment= mb_strimwidth($activity_sound_comment, 0, 20, "...","utf8");
		
		}
		
		$noti_str = $activity_user_name."さんが".$activity_sound_comment."に返信しました。";
		
	
		//リンク
		$noti_link_url="detail.php?id=".$row_noti["sound_id"];
	}

echo "<a href='".$noti_link_url."' style='display:block;border-bottom:1px solid #eee;padding:10px 10px 10px 10px;'>";

if($row_noti['type'] == 1 || $row_noti['type'] == 3){

	echo "<img src='userimg/".$noti_user_img."' style='float:left;width:45px;height:45px;border-radius:50px;'>";

}else if($row_noti['type'] == 2){
	
	echo "<img src='music_img_thumb/".$noti_sound_img."' style='float:left;width:45px;height:45px;border-radius:50px;'>";

}
echo "<div style='float:left;text-align:left;font-size:14px;background:;color:#333;width:320px;height:45px;margin-left:10px;overflow:hidden;'>".$noti_str."</div>";
echo "<div class='clear-fix'></div>";
echo "</a>";


}
if($noti_count==0){
	echo "<p style='font-size:18px;padding:10px 10px 10px 20px;text-align:left;color:#888;'>通知はまだありません。</p>";
}
print <<<EOC
</div>
</div>
EOC;


print<<<EOC
<i class="fa fa-globe" style='font-size:16px;margin-right:7px;'></i>通知

</div>

<a href="/likes.php?id=$user_id" class="top_menu_btn" style='width:140px;'><i class="fa fa-heart" style='font-size:16px;margin-right:5px;'></i>お気に入り</a>
<a href="creator.php?id=$user_id" class="top_menu_btn" style='width:140px;'><i class="fa fa-user" style='font-size:16px;margin-right:5px;'></i>マイページ</a>
<a href="/user/upload.php" class="top_menu_btn" style='width:140px;'><i class="fa fa-cloud-upload" style='font-size:16px;margin-right:5px;'></i>アップロード</a>

<div class='user_icon_menu'>
<img src='img_icon/noti_yajirushi.png' style='position:absolute;margin-top:-9px;margin-left:78%;'>
<p style='color:#333;padding:10px 20px 5px 20px;font-size:12px;font-weight:900;overflow:hidden;border-bottom:1px solid #eee;'>$user_name</p>
<a href='user'>ダッシュボード</a>
<a href='cgi/logout.php'>ログアウト</a>
</div>

EOC;




}else{
print <<<EOC
<a href="login.php?url=$url[0]" class="top_menu_btn" style='width:100px;'>ログイン</a>
<a href="register.php" class="top_menu_btn" style='width:120px;'>無料会員登録</a>
<a href="register.php" class="top_menu_btn" style='width:180px;'>3D サウンド録音方法</a>
<a href="soundways.php" class="top_menu_btn" style='width:150px;'>SoundWaysとは</a>



EOC;
}
?>
	

</div>