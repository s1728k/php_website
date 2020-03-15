<?php
require ('../env.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($conn->query('select 1 from users LIMIT 1') === FALSE)
{
	// sql to create table
	$sql = "CREATE TABLE users (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	avatar VARCHAR(1000),
	name VARCHAR(60) NOT NULL,
	email VARCHAR(70),
	phone VARCHAR(15),
	password VARCHAR(64),
	role VARCHAR(32),
	favorites VARCHAR(1000),
	last_logged_in datetime,
	p1 boolean,
	p2 boolean,
	p3 boolean,
	p4 boolean,
	p5 boolean,
	p6 boolean,
	p7 boolean,
	p8 boolean,
	p9 boolean,
	p10 boolean,
	p11 boolean,
	p12 boolean,
	p13 boolean,
	p14 boolean,
	p15 boolean,
	p16 boolean,
	p17 boolean,
	p18 boolean,
	p19 boolean,
	p20 boolean,
	p21 boolean,
	p22 boolean,
	p23 boolean,
	p24 boolean,
	p25 boolean,
	p26 boolean,
	p27 boolean,
	p28 boolean,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
	)";

	if ($conn->query($sql) === TRUE) {
	    echo "Table users created successfully<br>";
	} else {
	    echo "Error creating table: " . $conn->error."<br>";
	}

	$passwd = password_hash("admin@123", PASSWORD_DEFAULT);

	$sql = "INSERT INTO users (name, email, phone, role, password)
	VALUES ('Root Admin', 'admin@nomail.com', '9900990099', 'Admin', '$passwd')";

	if ($conn->query($sql) === TRUE) {
	    echo "Default admin created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}
}else{
	// sql to update table
	$sql = "ALTER TABLE users 
	-- ADD COLUMN last_logged_in datetime AFTER favorites
	";
    
	if ($conn->query($sql) === TRUE) {
	    echo "Table users updated successfully<br>";
	} else {
	    echo "Error updating table users: " . $conn->error."<br>";
	}
}

$conn->close();
?>