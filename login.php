<?php
	// define variables and set to empty values
	$emailErr = $passwordErr  = "";
	$email = $passwd = "";
	$message = "";
	include('env.php');

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
	  if (empty($_POST["email"])) {
	    $emailErr = "email is required";
	  } else {
	    $email = test_input($_POST["email"]);
	    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	      $emailErr = "Invalid email format";
	    }
	  }

	  if (empty($_POST["password"])) {
	    $passwordErr = "password is required";
	  } else {
	    $passwd = test_input($_POST["password"]);
	  }

	  if($emailErr == "" && $passwordErr == ""){

		  try {
			    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $stmt = $conn->prepare("SELECT id, avatar, name, email, password FROM MyGuests WHERE email='".$email."' LIMIT 1");
			    $stmt->execute();

			    // set the resulting array to associative
			    $result = $stmt->fetch(PDO::FETCH_ASSOC);
			    if(!$result){
			    	$message = "Email did not match";
			    }elseif($passwd != $result['password']){
			    	$message = "Password did not match";
			    }else{
			    	session_start();
			    	$_SESSION["id"] = $result['id'];
			    	$_SESSION["avatar"] = $result['avatar'];
			    	$_SESSION["email"] = $result['email'];
			    	$_SESSION["name"] = $result['name'];
			    	header("Location: $app_url/home.php");
			    	die();
			    }
			}
			catch(PDOException $e) {
			    $message = "Error: " . $e->getMessage();
			}
			$conn = null;
	  }


	}

	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
?>

<!DOCTYPE html>
<html>
<head>
  <?php require('layouts/styles.html'); ?>
  <style>
	.error {color: #FF0000;}
	</style>
</head>
<body>
	<?php require('layouts/nav.php'); ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<h3 class="text-center">Login</h3><br>

				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="row">
						<div class="col-md-4">
							<label>Email * </label>
						</div>
						<div class="col-md-6">
							<input type="email" class="form-control" name="email" value="<?php echo $email;?>">
							<span class="error"><?php echo $emailErr; ?></span>
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-4">
							<label>Password * </label>
						</div>
						<div class="col-md-6">
							<input type="password" class="form-control" name="password" value="<?php echo $passwd;?>">
							<span class="error"><?php echo $passwordErr; ?></span>
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-4">
						</div>
						<div class="col-md-6">
							<input type="submit" class="btn btn-primary">
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-9">
							<?php echo $message ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php require('layouts/scripts.html'); ?>
</body>
</html>

