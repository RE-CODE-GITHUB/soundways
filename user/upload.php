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
	$ok=0;
}

if($ok==1){
	header("Location: ../login.php");
}else{

//通知データベース
$soundways_top_activity_r = mysql_query("SELECT * from soundways_activity WHERE user_id = '".$user_id."' ORDER BY activity_id DESC LIMIT 0,4");



}



?><!DOCTYPE html>
<html lang="ja"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
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
//点滅
$("a").hover(
  function(){
    $(this).fadeTo("500","0.7");
  },function(){
    $(this).fadeTo("100","1");
  }
);



	$('#drag-area').bind('drop', function(e){
    // デフォルトの挙動を停止
    e.preventDefault();

    // ファイル情報を取得
    var files = e.originalEvent.dataTransfer.files;
 
    uploadFiles(files,1);
 
  }).bind('dragenter', function(){
    // デフォルトの挙動を停止
    return false;
  }).bind('dragover', function(){
    // デフォルトの挙動を停止
    return false;
  });
 
  /*================================================
    ダミーボタンを押した時の処理
  =================================================*/
  $('#btn').click(function() {
    // ダミーボタンとinput[type="file"]を連動
    $('.drag_area_input').click();
  });
  
  
  
  $('.btn2').click(function() {
    // ダミーボタンとinput[type="file"]を連動
    $('.img_area_input').click();
  });
  


  $('.drag_area_input').change(function(){
    // ファイル情報を取得
    var files = this.files;
    uploadFiles(files,1);
  });
  
  $('.img_area_input').change(function(){
    // ファイル情報を取得
    var files = this.files;
    uploadFiles(files,2);
  });
  
  var tag_count=0;
$(".tag_input").keypress(function(e){
	if(e.which == 13 ){
		if($(".tag_input").val()!=""){
	tag_count++;
$(".tag_after").before("<div class='sound_tag' id='"+tag_count+"'><div style='float:left;'>"+$(".tag_input").val()+"</div><div class='tag_barline_left'></div><div class='tag_barline_right'></div><div class='tag_del'><img src='img/cancel.png' style='width:20px;position:absolute;'></div><input type='hidden' value='"+$(".tag_input").val()+"' name='tag[]'></div>");

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
 
  if(type==1){
  
		// Ajaxでアップロード処理をするファイルへ内容渡す
		$.ajax({
		url: 'cgi/ajax_upload_sound.php',
		type: 'POST',
		data: fd,
		processData: false,
		contentType: false,
		success: function(data) {
		   upload = JSON.parse(data);
		  // alert(upload.filename);
		   var timestamp = new Date().getTime();
		   var tmp_str = "tmp_file/"+upload.filename+'?'+timestamp;
		   $(".upload_sound_on").show();
		   $("#drag-area").hide();
		   $(".tmp_sound_file").attr({value:upload.filename});
		   $(".upload_sound_on_audio").attr({src:tmp_str});
		}
		});
  
  }else{
	  
	  	// Ajaxでアップロード処理をするファイルへ内容渡す
		$.ajax({
		url: 'cgi/ajax_upload_img.php',
		type: 'POST',
		data: fd,
		processData: false,
		contentType: false,
		success: function(data) {
		   upload = JSON.parse(data);
		  // alert(upload.filename);
		   var timestamp = new Date().getTime();
		   var tmp_str = "tmp_img_file/"+upload.filename+'?'+timestamp;
		   $(".tmp_img_file").attr({value:upload.filename});
		   $(".upload_sound_img").css("background-image","url('"+tmp_str+"')");
		   $(".upload_sound_img").text("");
		   //$(".upload_sound_img").css("background","none");
		}
		});
	  
  }
  
  
  
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
			<div id="drag-area" style="background:#eee;padding:20px 0px 40px 0px;text-align:center;cursor:pointer;">
				<p style="padding-top:20px;"><img src="img_icon/onpu.png" style="width:160px;"></p>
				<p style="font-size:35px;padding-top:20px;color:#333;">
				アップロードする音声データを選択
				</p>
				<p style="font-size:35px;padding-top:10px;color:#888;">or</p>
				<p style="font-size:35px;padding-top:10px;color:#333;">
				音声データをドラック&ドロップする
				</p>
				<div class="btn-group">
					<input type="file" class="drag_area_input" name="upfile" multiple="multiple" style="display:none;">
					<button id="btn" style="margin-top:20px;width:200px;text-align:center;padding:10px 0px;color:white;background:#59b381;border:none;border-radius:10px;font-size:20px;cursor:pointer;">ファイルを選択</button>
				</div>
			</div>
			<div class="upload_sound_on" style="display:none;background:#eee;height:120px;text-align:center;">
			
			<audio src="tmp_file/" class="upload_sound_on_audio" style="margin-top:45px;" controls>
				<p>音声を再生するには、audioタグをサポートしたブラウザが必要です。</p>
			</audio> 

			</div>
			
			<form action="cgi/upload_sound.php" method="post" style="margin-top:20px;" enctype="multipart/form-data">
				<p style="margin:10px 0px;">タイトル</p>
				<input type="text" name="title" placeholder="サウンド名" style="padding-left:5px;width:98%;height:25px;line-height:25px;font-size:16px;" required>
				
				<p style="margin:20px 0px 10px 0px;">説明文</p>
				<textarea name="text" placeholder="サウンドの説明をしましょう" style="width:98%;padding-left:5px;height:80px;font-size:16px;" required></textarea>

				<p style="margin:10px 0px;">タグ</p>
				<div style="background:#eee;border-radius:0px;box-shadow:0px 0px 1px 1px #fff;padding:10px 10px;">
					<div style="padding:5px;background:;">
						<div class="tag_in_area">
							<div class="tag_after"></div>
							<div class="clear-fix"></div>
						</div>
						
						<input type="text" maxlength="50" class="tag_input" placeholder="タグを追加する" style="margin-top:15px;padding:5px 0px 5px 10px;border:none;width:98%;height:25px;line-height:25px;font-size:16px;">
						
					</div>
					
				</div>
				
				
				<p style="margin-top:20px;">ジャケット写真</p>
				<div style="margin-top:10px;">
					<input type="file" class="img_area_input" name="imgfile" style="display:none;">
					<div class="upload_sound_img" style="font-size:80px;color:#fff;background:#888;width:180px;background-size:100% 100%;text-align:center;height:180px;line-height:180px;float:left;margin-left:0px;"><i class="fa fa-question"></i></div>
					<div class="btn2" style="cursor:pointer;margin:70px 0px 0px 40px;padding:10px 20px;background:#333;width:400px;text-align:center;border-radius:30px;color:white;float:left;">ジャケット写真を選択する</div>
					<div class="clear-fix"></div>
				</div>
				<input type="hidden" class="tmp_sound_file" name="tmp_sound_file" value="">
				<input type="hidden" class="tmp_img_file" name="tmp_img_file" value="">
				<input type="submit" value="上記の内容で申請する" style="margin-top:30px;padding:10px 0px;color:white;border:0;width:98%;border-radius:20px;background:#ff5a5f;font-size:16px;cursor:pointer;">
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