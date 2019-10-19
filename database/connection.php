<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	require ('../env.php');

	// Create connection
	$conn = new mysqli($servername, $username, $password);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	echo "Connected successfully";
}
?>