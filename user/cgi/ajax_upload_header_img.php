<?php
session_start();
include("../../db.php");
$mail=$_SESSION['soundways_user_mail'];
$pass=$_SESSION['soundways_user_pass'];
$login_ok=0;
$v_ok=0;
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
	$user_id=$row['user_id'];
	$login_ok=1;
}


if($login_ok==1){




//かくちょうし更新
$imgtype = substr(strrchr($_FILES["upfile"]["name"], '.'), 1);

if($imgtype==="jpg" || $imgtype==="JPG" || $imgtype==="jpeg" || $imgtype==="JPEG" || $imgtype==="png" || $imgtype==="PNG" || $imgtype==="gif" || $imgtype==="GIF"){
	$ok=1;
}


if($_FILES["upfile"]["size"]>50000000){
	$ok=0;
	$ok_size=1;
}
	
if($ok==1){



		
	$str = $user_id."a3232dah2";
	$sound_tmp_id_hash = hash_hmac('sha256', $str, false);
	$filename = "tmp_h_".$sound_tmp_id_hash.".".$imgtype;
	
	$filename2 = "tmp_h_".$sound_tmp_id_hash;

	$filetmp = "../tmp_user_img/".$filename2.".*";
	foreach ( glob($filetmp) as $val ) {
		unlink("../tmp_user_img/".$val);
	}
	
				
		
			
	if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
	  if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "../tmp_user_img/" . $filename)) {
	    chmod("../tmp_user_img/".$filename, 0644);
	
			$array = array("filename"=>$filename);
	
	  } else {
		  $array = array("0");
	  }
	  
	  
  
}
  
  
}else{
	
	
}

		    


}else{
	
	
}

echo json_encode($array);


?>