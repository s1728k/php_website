<!DOCTYPE html>
<html>
<head>
  <?php require($app_key.'/view/layouts/styles.php'); ?>
  <style>
	.error {color: #FF0000;}
	</style>
</head>
<body>
	<?php require($app_key.'/view/layouts/nav.php'); ?>
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
	<?php require($app_key.'/view/layouts/scripts.php'); ?>
</body>
</html>

