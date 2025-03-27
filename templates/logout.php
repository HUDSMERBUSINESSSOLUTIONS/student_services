<?php
session_start();
include_once(__DIR__ . '/../components/loading.php');
showLoading();
session_destroy(); 
header("Location: login.php");
exit();
?>
