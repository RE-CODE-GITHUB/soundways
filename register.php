<!DOCTYPE html>
<html lang="ja"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SoundWays</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
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


<div style="width:1100px;margin:auto;padding-top:60px;padding-bottom:60px;">

	<div style="padding-top:50px;text-align:center;">
	
		<div style="border-radius:40px;padding:10px 40px;background:#305094;width:260px;color:white;font-size:20px;float:left;">
			Facebookで登録
		</div>
	
		<div style="border-radius:40px;padding:10px 40px;background:#00a9ea;width:260px;color:white;font-size:20px;float:left;margin-left:40px;">
			Twitterで登録
		</div>
	
		<div style="border-radius:40px;padding:10px 40px;background:#d84a39;width:260px;color:white;font-size:20px;float:left;margin-left:40px;">
			Google+で登録
		</div>
		
		<div class="clear-fix"></div>
		
	</div>
	
	<div style="margin-top:50px;">
		<div style="border-top:1px solid silver;width:47%;float:left;"></div>
		<div style="width:6%;float:left;text-align:center;color:silver;font-size:23px;background:;margin-top:-18px;">or</div>
		<div style="border-top:1px solid silver;width:47%;float:left;"></div>
		<div class="clear-fix"></div>
	</div>
	<div style="margin-top:20px;text-align:center;">
	<form action="cgi/register.php" method="post">
		<p style="font-size:20px;margin-bottom:10px;">メールアドレス</p>
		<p><input type="text" name="mail" style="text-align:center;width:60%;height:30px;font-size:18px;color:#1a1a1a;padding-left:5px;" placeholder="sample@soundways.com"></p>

		<p><input type="submit" value="登録する" style="cursor:pointer;padding:10px 0px;margin-top:35px;border:none;border-radius:20px;width:61%;hight:30px;background:#ff5a5f;font-size:16px;color:white;"></p>
	
	</form>
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