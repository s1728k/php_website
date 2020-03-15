<?php
// if (strpos($path, "/admin/") === 0){
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if($_POST['_token'] != $_SESSION[$_SERVER['HTTP_REFERER'].'_rand']){
      include($app_key.'/include/403.php');
      exit;
    }
  }
// }

//   if(!isset($_COOKIE[$cookie_name])) {
//     echo "Cookie named '" . $cookie_name . "' is not set!";
// } else {
//     echo "Cookie '" . $cookie_name . "' is set!<br>";
//     echo "Value is: " . $_COOKIE[$cookie_name];
// }
?>