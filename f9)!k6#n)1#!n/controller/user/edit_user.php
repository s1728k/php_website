<?php include($app_key.'/include/csrf_token.php'); ?>

<?php
include($app_key.'/model/User.php');

if(isset($_SESSION['old'])){
	$row = $_SESSION['old'];
	unset($_SESSION['old']);
	$error = $_SESSION['error'];
	unset($_SESSION['error']);

    $row['avatar'] = User::find($id,null,'avatar');
}else{
	$row = User::find($id);
}
$message = $_SESSION['message'];
unset($_SESSION['message']);
?>

<?php include($app_key.'/view/user/edit_user_view.php'); ?>