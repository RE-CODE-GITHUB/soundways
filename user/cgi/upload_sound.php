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


if(!empty($_POST["text"])){
	$text=$_POST["text"];
}else{
	$text="";
	$v_ok=0;
}
if(!empty($_POST["title"])){
	$title=$_POST["title"];
}else{
	$title="";
	$v_ok=0;
}

$t_count=0;
foreach($_POST["tag"] as $v){
	$tag[$t_count++]=$v;	
}


if(!empty($_POST["tmp_img_file"])){
	$tmp_img_file=$_POST["tmp_img_file"];
}else{
	$tmp_img_file="";
	$v_ok=0;
}


if(!empty($_POST["tmp_sound_file"])){
	$tmp_sound_file=$_POST["tmp_sound_file"];
}else{
	$tmp_sound_file="";
	$v_ok=0;
}


$ok_size=0;

if($_FILES["upfile"]["size"]>50000000){
	$ok=0;
	$ok_size=1;
}


/*判別 */
$title = str_replace(">", "&gt", $title);
$title = str_replace("<", "&lt", $title);
$tag = str_replace(">", "&gt", $tag);
$tag = str_replace("<", "&lt", $tag);


$text = str_replace(">", "&gt", $text);
$text = str_replace("<", "&lt", $text);
$text = str_replace("
", "<br>", $text);

//////////////////////////////////////////////////////////



	if($v_ok){

		//::::アルゴリズム::::

		//臨時イメージファイルのコピー/名前変更
		//JPEGサムネイル画像の作成
		//臨時サウンドファイルのコピー/名前変更
		//DBへのインサート

		
		$result = mysql_query('SELECT * from soundways_info');
		
		while ($row = mysql_fetch_assoc($result)) {
			$sound_img_num = $row["sound_img_num"]+1;
			$sound_num = $row["sound_num"]+1;
		}
		$sql = "UPDATE soundways_info SET sound_img_num = '$sound_img_num',sound_num = '$sound_num' WHERE id='1'";
		$result_flag = mysql_query($sql);
		
		//サウンドの名前
		$imgtype = substr(strrchr($tmp_sound_file, '.'), 1);
		$str = $sound_num."laddahoi".$user_id;
		$sound_tmp_id_hash = hash_hmac('sha256', $str, false);
		$main_sound_filename = "i".$sound_tmp_id_hash.".".$imgtype;
		
		//画像の名前
		$imgtype = substr(strrchr($tmp_img_file, '.'), 1);
		$str = $sound_img_num."laddahoi".$user_id;
		$sound_tmp_id_hash = hash_hmac('sha256', $str, false);
		$main_sound_img_filename = "s".$sound_tmp_id_hash.".".$imgtype;
		
		
		
		
		
						
		////画像jpg
	    $img_path ="../tmp_img_file/".$tmp_img_file;
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
		$filename_str = "s".$sound_tmp_id_hash;
		$jpg_filename = preg_replace("/(.+)(\.[^.]+$)/", "$1",$filename_str);
		imagejpeg($thumb_img,"../../music_img_thumb/".$jpg_filename.".jpg");
		//メモリ解放
		imagedestroy($thumb_img);
		///////////
		
		
		
		
		
		//サウンドのコピー
		rename("../tmp_file/".$tmp_sound_file, "../../music/".$main_sound_filename);
		
		//画像のコピー
		rename("../tmp_img_file/".$tmp_img_file, "../../music_img/".$main_sound_img_filename);
		
		//サウンドの削除
		$str = $user_id."dak2ak";
		$sound_tmp_id_hash = hash_hmac('sha256', $str, false);
		$img_filename = "tmp".$sound_tmp_id_hash;
		$filetmp = "../tmp_file/".$img_filename.".*";
		foreach ( glob($filetmp) as $val ) {
			unlink("../tmp_file/".$val);
		}
		
		//画像の削除
		$str = $user_id."aih2";
		$sound_tmp_id_hash = hash_hmac('sha256', $str, false);
		$sound_filename = "tmp".$sound_tmp_id_hash;
		$filetmp = "../tmp_img_file/".$sound_filename.".*";
		foreach ( glob($filetmp) as $val ) {
			unlink("../tmp_img_file/".$val);
		}
		

		
		
	    $time=date("Y-n-d H:i:s");
	  	//アップロード
	  	$sql = "INSERT INTO soundways_sound(sound_id,sound_title,sound_text,sound_img,sound_filename,user_id,time) VALUES ('$sound_img_num','$title','$text','$main_sound_img_filename','$main_sound_filename','$user_id','$time')";
	  	$result_flag = mysql_query($sql);
	  	
	  	
	  	//通知
	  	$sql = "INSERT INTO soundways_activity(user_id,time,type,sound_id) VALUES ('$user_id','$time','2','$sound_img_num')";
	  	$result_flag = mysql_query($sql);
	  	
	  	//サウンド数の加算
		$result3 = mysql_query("SELECT user_sound_num from soundways_user_info WHERE user_id ='".$user_id."'");
			while ($row3 = mysql_fetch_assoc($result3)) {
			$user_sound_num = $row3["user_sound_num"]+1;
		}
		$sql = "UPDATE soundways_user_info SET user_sound_num = '".$user_sound_num."' WHERE user_id ='".$user_id."'";
		$result_flag = mysql_query($sql);
	  	
	  	
	  	//アップロード
	  	foreach($tag as $v){
	  		$tag_sw=0;
	  		$result = mysql_query("SELECT tag_id from soundways_sound_tag WHERE tag_text='".$v."'");
	  		while ($row = mysql_fetch_assoc($result)) {
	  			$tag_sw=1;
	  			$tag_id=$row["tag_id"];
	  		}
	  			  		
	  		//すでにタグがあるかのチェック
	  		$tag_box_al_sw=0;
	  		$result = mysql_query("SELECT tag_id from soundways_sound_tag_box WHERE tag_id='".$tag_id."' AND sound_id='".$sound_id."'");
	  		while ($row = mysql_fetch_assoc($result)) {
		  		$tag_box_al_sw=1;
	  		}
	  		
	  		if($tag_box_al_sw==0 || $tag_sw==0){
		  		
	  		
		  		if($tag_sw==0){
		  			$result = mysql_query('SELECT * from soundways_info');
		  			while ($row = mysql_fetch_assoc($result)) {
						$tag_id = $row["tag_num"]+1;
					}
					$sql = "UPDATE soundways_info SET tag_num = '$tag_id' WHERE id='1'";
					$result_flag = mysql_query($sql);
			
		  			$sql = "INSERT INTO soundways_sound_tag(tag_id,tag_text) VALUES ('$tag_id','$v')";
		  			$result_flag = mysql_query($sql);
		  			
		  		}
		  		
		  		$sql = "INSERT INTO soundways_sound_tag_box(tag_id,sound_id) VALUES ('$tag_id','$sound_img_num')";
		  		$result_flag = mysql_query($sql);
		  		
		  		
	  			$result3 = mysql_query("SELECT COUNT(tag_id) as tag_n from soundways_sound_tag_box WHERE tag_id ='".$tag_id."'");
	  			while ($row3 = mysql_fetch_assoc($result3)) {
					$tag_kazu = $row3["tag_n"];
				}
				$sql = "UPDATE soundways_sound_tag SET tag_num = '".$tag_kazu."' WHERE tag_id='".$tag_id."'";
				$result_flag = mysql_query($sql);
				
				
	  		}
	  	}

		//ストリームを切る
		mysql_close($link);	
		header("Location: ../sound.php");
		
	}else{
	
		//ストリームを切る
		mysql_close($link);	
		header("Location: ../upload.php?error=1&size=".$ok_size);
	}



}else{

		//ストリームを切る
		mysql_close($link);	
		header("Location: ../../login.php");
	
}

?>