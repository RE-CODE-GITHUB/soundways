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
	
	if(!empty($_GET["id"])){
		$id=$_GET["id"];
	}else{
		$id=0;
	}
	
	$sound_r = mysql_query("SELECT * from soundways_sound WHERE user_id='".$user_id."' AND sound_id='".$id."'");
	
	while ($row = mysql_fetch_assoc($sound_r)) {
		$sound_id=$row["sound_id"];
		$sound_title=$row["sound_title"];
		$sound_text=$row["sound_text"];
		$time=$row["time"];
		$sound_img=preg_replace("/(.+)(\.[^.]+$)/", "$1",$row["sound_img"]).".jpg";
		$sound_filename=$row["sound_filename"];
	
	}

	//tag
	$tag_r = mysql_query("SELECT * from soundways_sound_tag_box WHERE sound_id='".$sound_id."'");
	
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



  $('.btn2').click(function() {
    // ダミーボタンとinput[type="file"]を連動
    $('.img_area_input').click();
  });
  
  $('.img_area_input').change(function(){
    // ファイル情報を取得
    var files = this.files;
 
    uploadFiles(files,2);
  });
  

$(".tag_input").keypress(function(e){
	if(e.which == 13 ){
		if($(".tag_input").val()!=""){
$(".tag_after").before("<div class='sound_tag'><div style='float:left;'>"+$(".tag_input").val()+"</div><div class='tag_barline_left'></div><div class='tag_barline_right'></div><div class='tag_del'><img src='img/cancel.png' style='width:20px;position:absolute;'></div><input type='hidden' value='"+$(".tag_input").val()+"' name='tag[]'></div>");

		$(".tag_input").val("");
		
		}
		return false;
	}
});
  

$(document).on('click',".tag_del",function(){
	$(this).parent().remove();
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
	
		// Ajaxでアップロード処理をするファイルへ内容渡す
	$.ajax({
	url: 'cgi/ajax_upload_img.php',
	type: 'POST',
	data: fd,
	processData: false,
	contentType: false,
	success: function(data) {
	   upload = JSON.parse(data);
	   //alert(upload.filename);
	   var timestamp = new Date().getTime();
	   var tmp_str = "tmp_img_file/"+upload.filename+'?'+timestamp;
	   $(".tmp_img_file").attr({value:upload.filename});
	   $(".upload_sound_img").attr({src:tmp_str});
	   $(".upload_sound_img_sw").attr({value:"1"});
	   $(".upload_sound_img").css("background","none");
	}
	});

}
</script>
</head>
<!------------------------------------------ body ----------------------------------------->
<body>
<div id="page">

<?php include("header.php"); ?>

<div style="margin:auto;width:1000px;padding-top:80px;">
<?php include("sidebar.php"); ?>
	<div style="float:right;width:770px;background:white;border-radius:5px;padding-bottom:20px;">
		<div style="padding:10px;">
			<div class="upload_sound_on" style="background:#eee;height:130px;text-align:center;">
			
			<audio src="../music/<?php echo $sound_filename;?>" class="upload_sound_on_audio" style="width:80%;margin-top:50px;" controls>
				<p>音声を再生するには、audioタグをサポートしたブラウザが必要です。</p>
			</audio> 

			</div>
			
			<form action="cgi/update_sound.php" method="post" style="margin-top:20px;" enctype="multipart/form-data">
				<p>タイトル</p>
				<input type="text" name="title" value="<?php echo $sound_title;?>" placeholder="sounds title" style="width:98%;height:25px;line-height:25px;font-size:16px;" required>
				
				<p style="margin-top:20px;">説明文</p>
				<textarea name="text" placeholder="text" style="width:98%;height:80px;font-size:16px;" required><?php
echo str_replace("<br>", "
", $sound_text);
?></textarea>



				<p style="margin:10px 0px;">タグ</p>
				<div style="background:#eee;border-radius:0px;box-shadow:0px 0px 1px 1px #fff;padding:10px 10px;">
					<div style="padding:5px;background:;">
						<div class="tag_in_area">
<?php

while ($row1 = mysql_fetch_assoc($tag_r)) {

	$tag_detail_r = mysql_query("SELECT * from soundways_sound_tag WHERE tag_id='".$row1["tag_id"]."'");
	while ($row2 = mysql_fetch_assoc($tag_detail_r)) {

print <<<EOC
<div class='sound_tag'><div style='float:left;'>$row2[tag_text]</div><div class='tag_barline_left'></div><div class='tag_barline_right'></div><div class='tag_del'><img src='img/cancel.png' style='width:20px;position:absolute;'></div><input type='hidden' value='$row2[tag_text]' name='tag[]'></div>
EOC;

	}
}
?>
							<div class="tag_after"></div>
							<div class="clear-fix"></div>
						</div>
						
						<input type="text" maxlength="50" class="tag_input" placeholder="タグを追加する" style="margin-top:15px;padding:5px 0px 5px 10px;border:none;width:98%;height:25px;line-height:25px;font-size:16px;">
						
					</div>
					
				</div>

				
								
				<p style="margin-top:20px;">ジャケット写真</p>
				<div style="margin-top:10px;">
					<input type="file" class="img_area_input" name="imgfile" style="display:none;">
					<img class="upload_sound_img" src="../music_img_thumb/<?php echo $sound_img;?>" style="background:#333;width:180px;height:180px;float:left;margin-left:0px;">
					<div class="btn2" style="cursor:pointer;margin:70px 0px 0px 40px;padding:10px 20px;background:#333;width:400px;text-align:center;border-radius:30px;color:white;float:left;">ジャケット写真を選択する</div>
					<div class="clear-fix"></div>
				</div>
				<input type="hidden" name="sound_id" value="<?php echo $id;?>">

				<input type="hidden" name="upload_sound_img_sw" class="upload_sound_img_sw" value="0">
				<input type="hidden" class="tmp_img_file" name="tmp_img_file" value="">
				
				<input type="submit" value="上記の内容を変更する" style="margin-top:30px;padding:10px 0px;color:white;border:0;width:98%;border-radius:20px;background:#ff5a5f;font-size:16px;cursor:pointer;">
			</form>
			<form action="cgi/sound_del.php" method="post">
			<input type="hidden" name="sound_id" value="<?php echo $id;?>">
			<input type="submit" value="音声データを削除する" style="margin-top:30px;padding:10px 0px;color:white;border:0;width:98%;border-radius:20px;background:#59c146;font-size:16px;cursor:pointer;">
			</form>
			
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