<?php
	// define variables and set to empty values
	$nameErr = $emailErr = $passwordErr = $password_confirmationErr = "";
	$name = $email = $passwd = $password_confirmation = "";
	$message = "";
	include('env.php');

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST["name"])) {
	    $nameErr = "Name is required";
	  } else {
	    $name = test_input($_POST["name"]);
	  }

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

	  if (empty($_POST["password_confirmation"])) {
	    $password_confirmationErr = "password_confirmation is required";
	  } else {
	    $password_confirmation = test_input($_POST["password_confirmation"]);
	    if($password_confirmation != $passwd){
	    	$password_confirmationErr = "password_confirmation did not match";
	    }
	  }

	  if($nameErr == "" && $emailErr == "" && $passwordErr == "" && $password_confirmationErr == ""){

		  try {
	    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			    // set the PDO error mode to exception
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			    $stmt = $conn->prepare('SELECT COUNT(email) AS EmailCount FROM MyGuests WHERE email = :email');
					$stmt->execute(array('email' => $email));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);

					if ($result['EmailCount'] == 0) {
					    $sql = "INSERT INTO MyGuests (name, email, password)
					    VALUES ('$name', '$email', '$passwd')";
					    // use exec() because no results are returned
					    $conn->exec($sql);
					    $message = "New record created successfully";
					}else{
						$emailErr = "Email exists";
					}

			    
			    }
			catch(PDOException $e)
			    {
			    $message = $sql . "<br>" . $e->getMessage();
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
				<h3 class="text-center">Register</h3><br>

				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="row">
						<div class="col-md-4">
							<label>Name * </label>
						</div>
						<div class="col-md-6">
							<input type="text" class="form-control" name="name" value="<?php echo $name;?>">
							<span class="error"><?php echo $nameErr; ?></span>
						</div>
					</div><br>
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
							<label>Confirm Password * </label>
						</div>
						<div class="col-md-6">
							<input type="password" class="form-control" name="password_confirmation" value="<?php echo $password_confirmation;?>">
							<span class="error"><?php echo $password_confirmationErr; ?></span>
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

