<?php
$servername = "localhost";
$username = "u598230515_kost";
$password = "kosampel";
$dbname = "u598230515_kost";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>