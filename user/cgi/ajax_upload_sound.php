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

if($imgtype==="mp3" || $imgtype==="MP3" || $imgtype==="wav" || $imgtype==="WAV" || $imgtype==="wave" || $imgtype==="WAVE"){
	$ok=1;
}


if($_FILES["upfile"]["size"]>50000000){
	$ok=0;
	$ok_size=1;
}
	
if($ok==1){


				
		$str = $user_id."dak2ak";
		$sound_tmp_id_hash = hash_hmac('sha256', $str, false);
		$filename = "tmp".$sound_tmp_id_hash.".".$imgtype;
		
		$filename2 = "tmp".$sound_tmp_id_hash;

		$filetmp = "../tmp_file/".$filename2.".*";
		foreach ( glob($filetmp) as $val ) {
			unlink("../tmp_file/".$val);
		}
	
	
	if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
	  if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "../tmp_file/" . $filename)) {
	    chmod("../tmp_file/".$filename, 0644);
			
		  	//アップロード
		//  	$sql = "INSERT INTO soundways_sound_tmp(sound_tmp_id,sound_filename,user_id) VALUES ('$sound_tmp_num','$filename','$user_id')";
		 // 	$result_flag = mysql_query($sql);
	
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