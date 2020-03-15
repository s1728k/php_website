<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	require ('../env.php');

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	$drops = [];

	foreach ($drops as $drop) {
		if ($conn->query("DROP TABLE $drop") === TRUE) {
		    echo "Table $drop deleted successfully<br>";
		} else {
		    echo "Error deleting table: " . $conn->error."<br>";
		}
	}

	$conn->close();
}
?>