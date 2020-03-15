<?php
include('env.php');

if($route['auth']=='user'){
  if(!$_SESSION[$app_key]['id']){
    header("Location: ".$app_url.'/login_form');
    exit;
  }
  
  // $roles = is_array($route['role'])?$route['role']:[$route['role']];
  // if(!in_array($_SESSION[$app_key]['role'],$roles) && $route['role']!='root'){
  // 	header("Location: ".$app_url.'/admin');
  //   exit;
  // }
  if($route['role']!="root" && !$_SESSION[$app_key][$route['role']]){
  	header("Location: ".$app_url.'/admin');
  	exit;
  }
}

if($route['auth']=='app'){
  if ($_POST['_token']!=$app_secret) {
    echo "Un Athorized!";
    exit;
  }
}

if($route['auth']=='app_user'){
	try {
	    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $stmt = $conn->prepare("SELECT api_token FROM contacts where id=".$_POST['id']."  LIMIT 1");
	    $stmt->execute();
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);

	    if ($_POST['_token']!=$row['api_token']) {
			echo "Un Athorized!";
			exit;
		}
	}catch(PDOException $e) {
	    echo "Error: " . $e->getMessage();
	}
	$conn = null;
}

?>