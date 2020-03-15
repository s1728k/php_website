<?php
$uploadErr = $message= "";
// echo '1'. $_POST['_token'].'<br>';
// echo '2'. $rand.'<br>';
// echo '3'. $_SESSION['rand'].'<br>';
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['_token']==$_SESSION['rand']) {
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
	    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	    if($check !== false) {
	        $uploadErr =  "File is an image - " . $check["mime"] . ".";
	        $uploadOk = 1;
	    } else {
	        $uploadErr =  "File is not an image.";
	        $uploadOk = 0;
	    }
	}
	// Check if file already exists
	if (file_exists($target_file)) {
	    $uploadErr =  "Sorry, file already exists.";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
	    $uploadErr =  "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    $uploadErr =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		// echo $uploadErr;
	    $uploadErr =  "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], __DIR__.'/'.$target_file)) {
	        $uploadErr =  "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	    } else {
	        $uploadErr =  "Sorry, there was an error uploading your file.";
	    }
	}
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['_token']==$_SESSION['rand']) {
	$_SESSION['rand']="";
	require('env.php');
	$path = $app_url.'/'.$target_file;
	// echo $path;
	try {
	    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    $sql = "UPDATE MyGuests SET avatar='".$path."' WHERE id=".$_SESSION["id"];

	    // Prepare statement
	    $stmt = $conn->prepare($sql);

	    // execute the query
	    $stmt->execute();

	    // echo a message to say the UPDATE succeeded
	    $message =  $stmt->rowCount() . " records UPDATED successfully";
	    $_SESSION["avatar"] = $path;
	    }
	catch(PDOException $e)
	    {
	    $message =  $sql . "<br>" . $e->getMessage();
	    }

	$conn = null;
}
?>
