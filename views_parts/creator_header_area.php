<div style="z-index:98;background:black;width:100%;height:430px;position:absolute;opacity:0.5;"></div>

<div id="creator_contents" style="padding-top:50px;background-image:url('userimg/<?php echo $creator_header_img;?>');background-size:100% auto;width:100%;height:380px;positioin:relative;">
	
	
	<div style="z-index:99;position:absolute;width:100%;">
		<div style="display:none;position:absolute;right:30px;top:30px;">
			<a href="" style="display:block;float:left;width:30px;margin-right:15px;">
				<img src="img_icon/creator_n_icon.png" style="width:30px;">
			</a>
			<a href="" style="display:block;float:left;width:30px;margin-right:15px;">
				<img src="img_icon/creator_t_icon.png" style="width:30px;">
			</a>
			<a href="" style="display:block;float:left;width:30px;">
				<img src="img_icon/creator_f_icon.png" style="width:30px;">
			</a>
			<div class="clear-fix"></div>
		</div>
		
		<div style="text-align:center;padding-top:35px;">
			<img src="userimg/<?php echo $creator_img;?>" style="box-shadow:0px 0px 1px 0px #333;border-radius:100px;width:150px;height:150px;">
			<p style="color:#fff;font-size:22px;text-shadow:0px 1px 1px #555;margin-top:15px;"><?php echo $creator_name;?></p>
			<p style="color:white;font-size:18px;text-shadow:0px 1px 1px #555;margin-top:15px;"><?php echo $creator_profile;?></p>
			<p style="color:white;font-size:18px;margin-top:20px;text-shadow:0px 1px 1px #555;"><?php echo $user_location;?><?php if($creator_url_sw==1){echo " - <a href='".$creator_url."' style='color:#fff;' target='_blanc'>".$creator_url."</a>";}?></p>
		</div>
	</div>


</div>
