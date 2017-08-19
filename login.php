<?php
session_start();
include("db.php");
$mail=$_SESSION['soundways_user_mail'];
$pass=$_SESSION['soundways_user_pass'];
$login_ok=0;

if(!empty($_GET["mail"])){
	$mail=$_GET["mail"];
}else{
	$mail="";
}

if(!empty($_GET["url"])){
	$re_url=$_GET["url"];
}else{
	$re_url="";
}

$re_url = str_replace("\"", "", $re_url);
$re_url = str_replace("'", "", $re_url);
$re_url = str_replace("<", "", $re_url);
$re_url = str_replace(">", "", $re_url);


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
	if(empty($row["user_img"])){
		$user_img= "tmp_img.jpg";
	}else{
		$user_img=$row['user_img'];
	}
	$login_ok=1;
}

mysql_close($link);
?><!DOCTYPE html>
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
	
		<div style="border-radius:40px;padding:10px 40px;background: #305094;width:260px;color:white;font-size:20px;float:left;">
			Facebookでログイン
		</div>
	
		<div style="border-radius:40px;padding:10px 40px;background:#00a9ea;width:260px;color:white;font-size:20px;float:left;margin-left:40px;">
			Twitterでログイン
		</div>
	
		<div style="border-radius:40px;padding:10px 40px;background:#d84a39;width:260px;color:white;font-size:20px;float:left;margin-left:40px;">
			Google+でログイン
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
	<form action="cgi/login.php" method="post">
		<input type="hidden" value="<?php echo $re_url;?>" name="re_url">
		<p style="font-size:20px;margin-bottom:10px;">メールアドレス</p>
		<p><input type="text" name="mail" value="<?php echo $mail;?>" style="text-align:center;width:60%;height:30px;font-size:18px;color:#1a1a1a;padding-left:5px;" placeholder="sample@soundways.com"></p>
	
		<p style="font-size:20px;margin-bottom:10px;margin-top:20px;">パスワード</p>
		<p><input type="password" name="pass" style="text-align:center;width:60%;height:30px;font-size:18px;color:#1a1a1a;padding-left:5px;"></p>
	
		<p><input type="submit" value="ログイン" style="cursor:pointer;padding:10px 0px;margin-top:35px;border:none;border-radius:20px;width:61%;hight:30px;background:#ff5a5f;font-size:16px;color:white;"></p>
	
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