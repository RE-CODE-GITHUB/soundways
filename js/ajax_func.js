//jQuery
$(document).ready(function(){

$(".likes_button").click(function(){
	
	likes_button($(this).val(),$(this).attr("id"));

});




$(".follow_button").click(function(){

	folow_button($(this).val(),$(this).attr("id"));

});




$(".goods_button").click(function(){

	goods_button($(this).val(),$(this).attr("id"));

});



$(".play").click(function(){
	
alert();

});



function goods_button(sound_id,id_name) {

	// Ajaxでアップロード処理をするファイルへ内容渡す
	$.ajax({
		url: '../cgi/ajax_goods_button.php',
		type: 'POST',
		data: {"sound_id":sound_id},
		success: function(data) {
			upload = JSON.parse(data);
			alert(upload.flg+":");
			if(upload.flg==1){
				$("#"+id_name).text("LIKES!!");
			}else{				
				$("#"+id_name).text("likes!");
			}
		}
	});

}




function likes_button(sound_id,id_name) {
	// Ajaxでアップロード処理をするファイルへ内容渡す
	$.ajax({
		url: '../cgi/ajax_likes_button.php',
		type: 'POST',
		data: {"sound_id":sound_id},
		success: function(data) {
			upload = JSON.parse(data);
			if(upload.flg==1){
				$("#"+id_name).text("Likes");
			}else{
				$("#"+id_name).text("Likes済み");
			}
		}
	});

}



function folow_button(creator_user_id,id_name) {
	// Ajaxでアップロード処理をするファイルへ内容渡す
	$.ajax({
		url: '../cgi/ajax_follow_button.php',
		type: 'POST',
		data: {"creator_user_id":creator_user_id},
		success: function(data) {
			upload = JSON.parse(data);
			if(upload.flg == 1){
				$("#"+id_name).text("フォローしています");
			}else{
				$("#"+id_name).text("フォローする");
			}
			
		}
	});

	
}




});