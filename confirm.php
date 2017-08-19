<?php
include("db.php");
$ok=1;
$hashok=0;

if(isset($_GET["error"])){
	$error=$_GET["error"];
}else{
	$error=0;
}

if(!empty($_POST["mailid"])){
	$mailid=$_POST["mailid"];
}else{
	$mailid="";
	$ok=0;
}


if(!empty($_POST["username"])){
	$username=$_POST["username"];
}else{
	$username="";
	$ok=0;
}


if(!empty($_POST["pass1"])){
	$pass1=$_POST["pass1"];
}else{
	$pass1="";
	$ok=0;
}

if(!empty($_POST["pass2"])){
	$pass2=$_POST["pass2"];
}else{
	$pass2="";
	$ok=0;
}

if($pass1 !== $pass2){

}
if(strlen($pass1)<6 || strlen($pass1)>50){
	$ok=0;
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

if($hashok==1){

	if($ok==0){
		header("Location: mainregister.php?mailid=".$mailid."&username=".$username);
	}else{
		
		
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
<meta name="viewport" content="width=device-width">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<link href="" rel="shortcut icon" type="image/x-icon">
<link rel='stylesheet' type='text/css' href='design.css'>
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

<p style="font-size:25px;padding-top:50px;">
あなたのプロフィールを作成しましょう。
</p>

<style>
table{
	box-shadow:0px 0px 2px 0px #888;
}
td{
	border: 1px solid silver;
	padding:10px 20px;
}
</style>

<form action="cgi/mainregister.php" method="post" style="margin-top:40px;">
<table rules="all">
<tr>
	<td>ニックネーム(団体名)</td>
	<td><?php echo $username;?></td>
</tr>
<tr>
	<td>メールアドレス</td>
	<td><?php echo $mail;?></td>
</tr>
<tr>
	<td>パスワード</td>
	<td><?php echo $pass1;?></td>
</tr>

</table>
<input type="hidden" name="username" value="<?php echo $username;?>">
<input type="hidden" name="pass" value="<?php echo $pass1;?>">
<input type="hidden" name="mail" value="<?php echo $mail;?>">
<input type="hidden" name="mailid" value="<?php echo $mailid;?>">
<input type="submit" value="登録する" style="border:0;border-radius:10px;margin-top:30px;font-size:18px;padding:10px 20px;background:#ff5a5f;color:white;cursor:pointer;">

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