<?php

if(!empty($_GET["mail"])){
	$mail=$_GET["mail"];
}else{
	$mail="";
}

?><!DOCTYPE html>
<html lang="ja"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SoundWays</title>
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

<?php
include("top_bar.php");
?>

<!-- -->
<div class="under_bar">
	
</div>


<div style="width:1100px;margin:auto;padding-top:60px;padding-bottom:60px;text-align:center;">

<p style="padding-top:50px;font-size:25px;"><?php echo $mail;?></p>

<p style="font-size:25px;padding-top:50px;">
あなたのメールアドレスに本登録用URLを送信致しました。
</p>

<div style="margin-top:50px;">
	<a href="https://login.yahoo.co.jp/config/login?.src=ym&.done=http%3A%2F%2Fmail.yahoo.co.jp%2F" style="color:#444;font-size:18px;display:block;float:left;width:300px;height:45px;line-height:45px;box-shadow:0px 0px 1px 0px #aaa;">
		Yahoo!メール ログインページ
	</a>
	<a href="https://accounts.google.com/ServiceLogin?service=mail&continue=https://mail.google.com/mail/&hl=ja" style="color:#444;font-size:18px;display:block;margin-left:100px;float:left;width:300px;height:45px;line-height:45px;box-shadow:0px 0px 1px 0px #aaa;">
		Gmail ログインページ
	</a>
	<a href="https://login.live.com/login.srf?wa=wsignin1.0&ct=1409066173&rver=6.1.6206.0&sa=1&ntprob=-1&wp=MBI_SSL_SHARED&wreply=https:%2F%2Fmail.live.com%2F%3Fowa%3D1%26owasuffix%3Dowa%252f&id=64855&snsc=1&cbcxt=mail" style="color:#444;font-size:18px;display:block;float:right;width:300px;height:45px;line-height:45px;box-shadow:0px 0px 1px 0px #aaa;">
		OutLook ログインページ
	</a>
	<div class="clear-fix"></div>
</div>

</div>


<?php
include("footer2.php");
?>

<!-------------- page end -------------->
</div>
<!-- コメント -->
</body>
</html>