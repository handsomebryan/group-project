<?php
// Database connection variables
$servername = "localhost";
$username = "root";
$password = "00000000";
$dbname = "fyp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

// Use this connection in your other PHP scripts
// You can close this connection in other scripts using $conn->close();
?>
