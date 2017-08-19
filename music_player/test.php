<?php
if ($handle = opendir('./')) {
    while (false !== ($file = readdir($handle))) {
   		if($file != ".." && $file != "." && $file != "test.php" && $file != "clarity_player"){
   			$ar=explode("\\", $file);
   			$str="";
   			foreach($ar as $v){
	   				
	   			if(strpos($v, ".")>0){
	   				$str.=$v;
		   			echo "[".$v."]";
		   			rename($file, $str);
	   			}else{
	   				$str.=$v."/";
		   			echo $v."@";
		   			
					if (!file_exists($str)) {
						mkdir($str, 0755);
					}
	   			}
	   			echo "<h1>".$str."</h1>";
	   			echo "<hr>";
   			}
   			
	    }
    }
    closedir($handle);
}
?> 