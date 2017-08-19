<?php
session_start();
$_SESSION['soundways_user_mail'] = "";
$_SESSION['soundways_user_pass'] = "";
header("Location: ../../");
?>