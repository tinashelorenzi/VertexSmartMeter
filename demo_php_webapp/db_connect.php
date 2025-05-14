<?php
date_default_timezone_set('Africa/Johannesburg');
$servername = "localhost";
$username = "vertexcg_vsmxxps";
$password = "Arj1V54qWWSbChSN1zY*";
$dbname = "vertexcg_VSM_data";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("SET time_zone = '+02:00'");

// Set PHP timezone as well
date_default_timezone_set('Africa/Johannesburg');
?>
