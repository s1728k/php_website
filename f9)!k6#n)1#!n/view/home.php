<!DOCTYPE html>
<html>
<head>
  <?php require($app_key.'/view/layouts/styles.php'); ?>
</head>
<body>
	<?php require($app_key.'/view/layouts/nav.php'); ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">
						<image src="<?php echo $_SESSION["avatar"]??'https://via.placeholder.com/150'; ?>" style="width: 100px; height: 100px;">
					</div>
					<div class="col-md-6">
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
						    <input type="hidden" value="<?php echo $rand; ?>" name="_token" />
						    <input type="file" name="fileToUpload" id="fileToUpload">
						    <input type="submit" value="Upload Image" name="submit">
						</form>
						<span class="error"><?php echo $uploadErr; ?></span>
					</div>
				</div><br>
				<div class="row">
					<div class="col-md-4">
						<label>Name:</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="name" id="name" value="<?php echo $_SESSION["name"]; ?>">
					</div>
				</div><br>
				<div class="row">
					<div class="col-md-4">
						<label>Email:</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="email" id="email" value="<?php echo $_SESSION["email"]; ?>">
					</div>
				</div><br>
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-9">
						<?php echo $message ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php require($app_key.'/view/layouts/scripts.php'); ?>
</body>
</html>