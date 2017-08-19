<?php
session_start();
include("../db.php");

$ok=1;
if(!empty($_SESSION["soundways_user_mail"])){
	$mail=$_SESSION['soundways_user_mail'];
}else{
	$mail="";
	$ok=1;
}

if(!empty($_SESSION['soundways_user_pass'])){
	$pass=$_SESSION['soundways_user_pass'];
}else{
	$pass="";
	$ok=1;
}







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

$mail = str_replace("'", "", $mail);
$mail = str_replace("\"", "", $mail);
$pass = str_replace("'", "", $pass);
$pass = str_replace("\"", "", $pass);

//メールアドレスが登録されているかの判別.
$result = mysql_query("SELECT user_id from soundways_user_info WHERE user_mail = '$mail' AND user_pass = '$pass' ");

while ($row = mysql_fetch_assoc($result)) {
	$user_id=$row['user_id'];
	$ok=0;
}

	$v_ok=1;
	if(!empty($_POST["sound_id"])){
		$sound_id=$_POST["sound_id"];
	}else{
		$sound_id=0;
		$v_ok=0;
	}



if($ok==1){



if(!empty($_SESSION['soundways_free_user_id'])){
	$free_user_id=$_SESSION['soundways_free_user_id'];
}else{
	$ipAddress = $_SERVER["REMOTE_ADDR"];
	// IPアドレスを数値として取得する場合
	$_SESSION['soundways_free_user_id']=ip2long($ipAddress).date("YndHis");
	$free_user_id=ip2long($ipAddress).date("YndHis");
}



	//すでにしているかどうか
	$good_ok=0;
	$result = mysql_query("SELECT goods_id from soundways_goods_box WHERE ip_id = '".$free_user_id."' AND sound_id = '".$sound_id."'");
	while ($row = mysql_fetch_assoc($result)) {
		$goods_id=$row['goods_id'];
		$good_ok=1;
	}



		if($good_ok==1){
			//いいねしている場合
			
			
			$sql = "DELETE FROM soundways_goods_box WHERE goods_id = '".$goods_id."'";
			$result_flag = mysql_query($sql);
	
			
			//減算する
			$result = mysql_query("SELECT sound_goods from soundways_sound WHERE sound_id = '".$sound_id."'");
			while ($row = mysql_fetch_assoc($result)) {
				$sound_goods_num=$row['sound_goods']-1;
				if($sound_goods_num<0){
					$sound_goods_num=0;
				}
			}
			$sql = "UPDATE soundways_sound SET sound_goods = '".$sound_goods_num."' WHERE sound_id = '".$sound_id."'";
			$result_flag = mysql_query($sql);


/*

		  	//通知
		  	$sql = "INSERT INTO soundways_activity(user_id,time,type,sound_id,second_user_id) VALUES ('$r_user_id','$time','1','$sound_id','$user_id')";
		  	$result_flag = mysql_query($sql);
		  	
		  	
		  	//notification数の加算
			$result = mysql_query("SELECT user_notification_num from soundways_user_info WHERE user_id = '".$r_user_id."'");
			while ($row4 = mysql_fetch_assoc($result)) {
				$user_notification_num=$row4['user_notification_num']+1;
			}
			
			$sql = "UPDATE soundways_user_info SET user_notification_num = '$user_notification_num' WHERE user_id = '".$r_user_id."'";
			$result_flag = mysql_query($sql);
		
			//notificationデータの追加
		  	$sql = "INSERT INTO soundways_notification_box(user_id,time,type,sound_id,fire_user_id) VALUES ('$r_user_id','$time','1','$sound_id','$user_id')";
		  	$result_flag = mysql_query($sql);

*/

			
			
		}else{
			//いいねしてない場合
			
			
			$sql = "INSERT INTO soundways_goods_box (ip_id,sound_id) VALUES ('$free_user_id','$sound_id')";
			$result_flag = mysql_query($sql);
			
			//加算する
			$result = mysql_query("SELECT sound_goods from soundways_sound WHERE sound_id = '".$sound_id."'");
			while ($row = mysql_fetch_assoc($result)) {
				$sound_goods_num=$row['sound_goods']+1;
			}
			$sql = "UPDATE soundways_sound SET sound_goods = '".$sound_goods_num."' WHERE sound_id = '".$sound_id."'";
			$result_flag = mysql_query($sql);
			
		}




	mysql_close($link);
	$array=array("goods_flg"=>$good_ok,"error_num"=>1,"goods_num"=>$sound_goods_num);
	
}else{


$sound_id = str_replace(">", "&gt", $sound_id);
$sound_id = str_replace("<", "&lt", $sound_id);
$sound_id = str_replace("'", "", $sound_id);
$sound_id = str_replace("\"", "", $sound_id);

	if($v_ok==1){

		//すでにしているかどうか
		$good_ok=0;
		$result = mysql_query("SELECT goods_id from soundways_goods_box WHERE user_id = '".$user_id."' AND sound_id = '".$sound_id."'");
		while ($row = mysql_fetch_assoc($result)) {
			$goods_id=$row['goods_id'];
			$good_ok=1;
		}
		
		if($good_ok==1){
			//いいねしている場合
			
			
			$sql = "DELETE FROM soundways_goods_box WHERE goods_id = '".$goods_id."'";
			$result_flag = mysql_query($sql);
	
			
			//減産する
			$result = mysql_query("SELECT sound_goods from soundways_sound WHERE sound_id = '".$sound_id."'");
			while ($row = mysql_fetch_assoc($result)) {
				$sound_goods_num=$row['sound_goods']-1;
				if($sound_goods_num<0){
					$sound_goods_num=0;
				}
			}
			$sql = "UPDATE soundways_sound SET sound_goods = '".$sound_goods_num."' WHERE sound_id = '".$sound_id."'";
			$result_flag = mysql_query($sql);
			
			
		}else{
			//いいねしてない場合
			
			
			$sql = "INSERT INTO soundways_goods_box (user_id,sound_id) VALUES ('$user_id','$sound_id')";
			$result_flag = mysql_query($sql);
			
			//加算する
			$result = mysql_query("SELECT sound_goods from soundways_sound WHERE sound_id = '".$sound_id."'");
			while ($row = mysql_fetch_assoc($result)) {
				$sound_goods_num=$row['sound_goods']+1;
			}
			$sql = "UPDATE soundways_sound SET sound_goods = '".$sound_goods_num."' WHERE sound_id = '".$sound_id."'";
			$result_flag = mysql_query($sql);
			
		
		}

		
		$array=array("goods_flg"=>$good_ok,"error_num"=>1,"goods_num"=>$sound_goods_num);
	

		mysql_close($link);
		
	}else{
		$array=array("goods_flg"=>$good_ok,"error_num"=>2);
		mysql_close($link);
	}




}

echo json_encode($array);


?>