<?php

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "vertexcg_VSM_data";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>