<?php
session_start();
include("db.php");
$mail=$_SESSION['soundways_user_mail'];
$pass=$_SESSION['soundways_user_pass'];
$login_ok=0;

$ok=0;
$hashok=0;

if(isset($_GET["error"])){
	$error=$_GET["error"];
}else{
	$error=0;
}

if(!empty($_GET["mailid"])){
	$mailid=$_GET["mailid"];
}else{
	$mailid="";
}

if(!empty($_GET["username"])){
	$username=$_GET["username"];
}else{
	$username="";
}

$link = mysql_connect($databasedomain,$databaseid,$databasepass);
if (!$link) {die('接続失敗です。'.mysql_error());}
$db_selected = mysql_select_db($databasename, $link);
if (!$db_selected){die('データベース選択失敗です。'.mysql_error());}
//文字コード設定
mysql_set_charset('utf8');


$result = mysql_query("SELECT * from soundways_kari WHERE mail_hash = '".$mailid."'");
while ($row = mysql_fetch_assoc($result)) {
	$hashok=1;
	$mail=$row["mail"];
}

if($hashok){


$result = mysql_query("SELECT * from soundways_user_info WHERE user_mail = '".$mail."' AND user_pass = '".$pass."'");

while ($row = mysql_fetch_assoc($result)) {
	$user_name=$row['user_name'];
	$user_id=$row['user_id'];
	$user_img=$row['user_img'];
	$user_notification_num=$row['user_notification_num'];
	$login_ok=1;
}




	mysql_close($link);

}else{

	mysql_close($link);
	header("Location: register.php");

}
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
<div id="page" style="background:#f1f1f1;">

<?php
include("top_bar.php");
?>

<!-- -->
<div class="under_bar">
	
</div>


<div style="width:1100px;margin:auto;padding-top:60px;padding-bottom:60px;">

<p style="font-size:25px;padding-top:50px;text-align:center;">
下記の入力フォームを完成させましょう。
</p>

<style>
table{
	box-shadow:0px 0px 1px 0px #888;
	width: 100%;
}
td{
	border: 1px solid #ddd;
}
.left_td{
	width:28%;
	padding-left: 2%;
	height: 60px;
	line-height: 60px;
	
}
.right_td{
	width:70%;
	height: 60px;
	line-height: 60px;
}
</style>

<form action="cgi/mainregister.php" method="post" style="margin:auto;margin-top:40px;width:700px;">
<table rules="all" style="background:white;">
<tr>
	<td class="left_td">メールアドレス</td>
	<td class="right_td"><p style="padding-left: 2%;font-size:18px;color:#111;"><?php echo $mail;?></p></td>
</tr>
<tr>
	<td class="left_td">名前</td>
	<td class="right_td"><input type="text" name="username"  maxlength="30" placeholder="(例)伊勢 隼人 , Hayato Ise , SoundWays" value="<?php echo $username;?>" style="border:0;box-sizing: border-box;width:98%;padding-left:2%;height:60px;line-height:60px;font-size:16px;color:#1a1a1a;" autofocus required></td>
</tr>

<tr>
	<td class="left_td">パスワード<span style="font-size:14px;">(6文字以上)</span></td>
	<td class="right_td"><input type="password" name="pass1" maxlength="50" style="border:0;box-sizing: border-box;width:98%;padding-left:2%;height:60px;line-height:60px;font-size:16px;color:#1a1a1a;" required></td>
</tr>
<tr>
	<td class="left_td">確認用パスワード</td>
	<td class="right_td"><input type="password" name="pass2" maxlength="50" style="border:0;box-sizing: border-box;width:98%;padding-left:2%;height:60px;line-height:60px;font-size:16px;color:#1a1a1a;" required></td>
</tr>
</table>
<input type="hidden" name="mailid" value="<?php echo $mailid;?>">
<input type="submit" value="登録を完了する" style="border:0;border-radius:30px;margin-top:30px;font-size:18px;padding:10px 20px;background:#59c146;color:white;cursor:pointer;width:100%;">

</form>

</div>


<?php
include("footer.php");
?>

<!-------------- page end -------------->
</div>
<!-- コメント -->
</body>
</html>