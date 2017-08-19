$(document).ready(function(){

$(".user_icon").click(function(){
	
	$(".user_icon_menu").toggle();
	$(".notification_menu").hide();
});


$(".noti_icon").click(function(){
	
	$(".notification_menu").toggle();
	$(".user_icon_menu").hide();
});



});