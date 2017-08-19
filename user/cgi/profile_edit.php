<?php
session_start();
include("../../db.php");
$mail=$_SESSION['soundways_user_mail'];
$pass=$_SESSION['soundways_user_pass'];
$login_ok=0;
$v_ok=1;
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
	$login_ok=1;
}


if($login_ok==1){


if(!empty($_POST["name"])){
	$name = htmlspecialchars($_POST["name"],ENT_QUOTES);
}else{
	$name="";
	$v_ok=0;
}

if(!empty($_POST["profile"])){
	$profile = htmlspecialchars($_POST["profile"],ENT_QUOTES);
}else{
	$profile = "";
	$v_ok=0;
}

if(!empty($_POST["location"])){
	$location = htmlspecialchars($_POST["location"],ENT_QUOTES);
}else{
	$location = "";
}

if(!empty($_POST["url"])){
	$url = htmlspecialchars($_POST["url"],ENT_QUOTES);
}else{
	$url = "";
}


if(!empty($_POST["tmp_header_img_file"])){
	$tmp_header_img_file=htmlspecialchars($_POST["tmp_header_img_file"],ENT_QUOTES);
}else{
	$tmp_header_img_file=0;
}

if(!empty($_POST["tmp_user_img_file"])){
	$tmp_user_img_file=htmlspecialchars($_POST["tmp_user_img_file"],ENT_QUOTES);
}else{
	$tmp_user_img_file=0;
}



$name = htmlspecialchars($name, ENT_QUOTES);
$profile = htmlspecialchars($profile, ENT_QUOTES);
$profile = str_replace("
", "<br>",$profile);

//////////////////////////////////////////////////////////



	if($v_ok){

		//::::アルゴリズム::::
		//臨時イメージファイルのコピー/名前変更
		//臨時サウンドファイルのコピー/名前変更
		//DBへのインサート


		$sql = "UPDATE soundways_user_info SET user_name='".$name."',user_profile='".$profile."',user_location='".$location."',user_url='".$url."' WHERE user_id='".$user_id."'";
		$result_flag = mysql_query($sql);
  	
  		//画像の更新
if(!empty($tmp_user_img_file)){

			$result = mysql_query("SELECT user_img from soundways_user_info WHERE user_id='".$user_id."' ");
			while ($row = mysql_fetch_assoc($result)) {
				$user_img=$row['user_img'];
			}
			
			//元にあるデータの削除	
			$jpg_filename = preg_replace("/(.+)(\.[^.]+$)/", "$1",$user_img);

			unlink("../../userimg/".$user_img);
			unlink("../../userimg_thumb/".$jpg_filename.".jpg");
		
		
			//画像の名前
			$imgtype = substr(strrchr($tmp_user_img_file, '.'), 1);
			$str = "lewdi".$user_id;
			$sound_tmp_id_hash = hash_hmac('sha256', $str, false);
			$main_sound_img_filename = "u".$sound_tmp_id_hash.".".$imgtype;
			
			////画像jpg
		    $img_path ="../tmp_user_img/".$tmp_user_img_file;
		    list($width,$height,$format) = getimagesize($img_path);
		    
			//format確認
			$format=image_type_to_extension($format);
			$type=0;
			if($format === ".png"){$type=1;}
			if($format === ".jpeg"){$type=2;}
			if($format === ".gif"){$type=3;}
			
			$new_width = "600";
			$new_height = round($new_width / $width , 2) * $height;
			
			if($type==1){
				//png
				$source_img = imagecreatefrompng($img_path);
			}else if($type==2){
				//jpg
				$source_img = imagecreatefromjpeg($img_path);
			}else if($type==3){
				//gif
				$source_img = imagecreatefromgif($img_path);
			}else{
				//none
			}
			
			$thumb_img = imagecreatetruecolor($new_width , $new_height);
			imagecopyresized($thumb_img , $source_img , 0 , 0 , 0 ,0 , $new_width , $new_height , $width , $height);
			//作成
			
			$jpg_filename = preg_replace("/(.+)(\.[^.]+$)/", "$1",$main_sound_img_filename);
			imagejpeg($thumb_img,"../../userimg_thumb/".$jpg_filename.".jpg");
			//メモリ解放
			imagedestroy($thumb_img);
			///////////
	
			//画像のコピー
			rename("../tmp_user_img/".$tmp_user_img_file, "../../userimg/".$main_sound_img_filename);

			//temp画像の削除
			$sound_filename = preg_replace("/(.+)(\.[^.]+$)/", "$1",$tmp_img_file);
			$filetmp = "../tmp_user_img/".$sound_filename.".*";
			foreach ( glob($filetmp) as $val ) {
				unlink("../tmp_user_img/".$val);
			}

			$sql = "UPDATE soundways_user_info SET user_img='".$main_sound_img_filename."' WHERE user_id='".$user_id."'";
			$result_flag = mysql_query($sql);
  	
  	
}



if(!empty($tmp_header_img_file)){

			$result = mysql_query("SELECT user_header_img from soundways_user_info WHERE user_id='".$user_id."' ");
			while ($row = mysql_fetch_assoc($result)) {
				$user_img=$row['user_header_img'];
			}
			
			//元にあるデータの削除	
			$jpg_filename = preg_replace("/(.+)(\.[^.]+$)/", "$1",$user_img);

			unlink("../../userimg/".$user_img);
		
		
			//画像の名前
			$imgtype = substr(strrchr($tmp_header_img_file, '.'), 1);
			$str = "leddwdi".$user_id;
			$sound_tmp_id_hash = hash_hmac('sha256', $str, false);
			$main_sound_img_filename = "h_".$sound_tmp_id_hash.".".$imgtype;
			
	
			//画像のコピー
			rename("../tmp_user_img/".$tmp_header_img_file, "../../userimg/".$main_sound_img_filename);

			//temp画像の削除
			$sound_filename = preg_replace("/(.+)(\.[^.]+$)/", "$1",$tmp_img_file);
			$filetmp = "../tmp_user_img/".$sound_filename.".*";
			foreach ( glob($filetmp) as $val ) {
				unlink("../tmp_user_img/".$val);
			}

			$sql = "UPDATE soundways_user_info SET user_header_img='".$main_sound_img_filename."' WHERE user_id='".$user_id."'";
			$result_flag = mysql_query($sql);
  	
  	
}




		//ストリームを切る
		mysql_close($link);	
		header("Location: ../../creator.php?id=".$user_id);
		
	}else{
	
		//ストリームを切る
		mysql_close($link);	
		header("Location: ../profile.php?error=1");
	}



}else{

		//ストリームを切る
		mysql_close($link);	
		header("Location: ../../login.php");
	
}

?>