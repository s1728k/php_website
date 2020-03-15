<?php 
if ($_SERVER["REQUEST_METHOD"] == "GET"){
	session_start(); 
    $rand=rand();
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $_SESSION[$actual_link.'_rand']=$rand;
}
?>