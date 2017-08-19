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

if(!empty($_POST["sound_id"])){
	$sound_id=$_POST["sound_id"];
}else{
	$sound_id=0;
	$v_ok=0;
}

if(!empty($_POST["upload_sound_img_sw"])){
	$upload_sound_img_sw=$_POST["upload_sound_img_sw"];
}else{
	$upload_sound_img_sw=0;
}

if(!empty($_POST["tmp_img_file"])){
	$tmp_img_file=$_POST["tmp_img_file"];
}else{
	$tmp_img_file="";
	if($upload_sound_img_sw==1){
		$v_ok=0;
	}
}

$t_count=0;
foreach($_POST["tag"] as $v){
	$tag[$t_count++]=$v;	
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
		//臨時サウンドファイルのコピー/名前変更
		//DBへのインサート


		$sql = "UPDATE soundways_sound SET sound_title='".$title."',sound_text='".$text."' WHERE sound_id='".$sound_id."'";
		$result_flag = mysql_query($sql);

	  	//tagの削除
	  	$tag_del_r = mysql_query("SELECT * from soundways_sound_tag_box WHERE sound_id='".$sound_id."'");
	  	while ($tag_del_row = mysql_fetch_assoc($tag_del_r)) {
		  	
		  	$tag_del_r_d = mysql_query("SELECT * from soundways_sound_tag WHERE tag_id='".$tag_del_row["tag_id"]."'");
		  	while ($tag_del_row_d = mysql_fetch_assoc($tag_del_r_d)) {
				$tag_del_num = $tag_del_row_d["tag_num"]-1;
			}
			
				$sql = "DELETE FROM soundways_sound_tag_box WHERE sound_id = '".$tag_del_row["sound_id"]."'";
				$result_flag = mysql_query($sql);
		  		
		  		$sql = "UPDATE soundways_sound_tag SET tag_num = '".$tag_del_num."' WHERE tag_id='".$tag_del_row["tag_id"]."'";
		  		$result_flag = mysql_query($sql);
			
			
		}
		
		
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
		  		
		  		$sql = "INSERT INTO soundways_sound_tag_box(tag_id,sound_id) VALUES ('$tag_id','$sound_id')";
		  		$result_flag = mysql_query($sql);
		  		 	  		
	  			$result3 = mysql_query("SELECT COUNT(tag_id) as tag_n from soundways_sound_tag_box WHERE tag_id ='".$tag_id."'");
	  			while ($row3 = mysql_fetch_assoc($result3)) {
					$tag_kazu = $row3["tag_n"];
				}
				$sql = "UPDATE soundways_sound_tag SET tag_num = '".$tag_kazu."' WHERE tag_id='".$tag_id."'";
				$result_flag = mysql_query($sql);
			
			}
	  	
	  	
	  	}
	  	
	  	
  	
  		//画像の更新
  		if($upload_sound_img_sw==1){
	  		
	  		
			$result = mysql_query("SELECT sound_img from soundways_sound WHERE sound_id='".$sound_id."' ");
			while ($row = mysql_fetch_assoc($result)) {
				$sound_img=$row['sound_img'];
			}
			
			//元にあるデータの削除	
			$jpg_filename = preg_replace("/(.+)(\.[^.]+$)/", "$1",$sound_img);

			unlink("../../music_img/".$sound_img);
			unlink("../../music_img_thumb/".$jpg_filename.".jpg");
		
		
			//画像の名前
			$imgtype = substr(strrchr($tmp_img_file, '.'), 1);
			$str = $sound_id."laddahoi".$user_id;
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
			
			$jpg_filename = preg_replace("/(.+)(\.[^.]+$)/", "$1",$main_sound_img_filename);
			imagejpeg($thumb_img,"../../music_img_thumb/".$jpg_filename.".jpg");
			//メモリ解放
			imagedestroy($thumb_img);
			///////////
	
			//画像のコピー
			rename("../tmp_img_file/".$tmp_img_file, "../../music_img/".$main_sound_img_filename);

			//temp画像の削除
			$sound_filename = preg_replace("/(.+)(\.[^.]+$)/", "$1",$tmp_img_file);
			$filetmp = "../tmp_img_file/".$sound_filename.".*";
			foreach ( glob($filetmp) as $val ) {
				unlink("../tmp_img_file/".$val);
			}

			$sql = "UPDATE soundways_sound SET sound_img='".$main_sound_img_filename."' WHERE sound_id='".$sound_id."'";
			$result_flag = mysql_query($sql);
  	
  	
  		}



		//ストリームを切る
		mysql_close($link);	
		header("Location: ../sound_detail.php?id=".$sound_id);
		
	}else{
	
		//ストリームを切る
		mysql_close($link);	
		header("Location: ../sound_detail.php?id=".$sound_id."?error=1");
	}



}else{

		//ストリームを切る
		mysql_close($link);	
		header("Location: ../../login.php");
	
}

?>