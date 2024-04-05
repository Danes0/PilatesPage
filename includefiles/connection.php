<?php
$servername="localhost";
$username="root";
$password="dani1234";
$database="db_pilates";

//create connetion
$conn = new mysqli($servername, $username, $password, $database);

// Display  an error message if the connection fails
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
