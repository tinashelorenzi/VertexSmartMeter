<?php
// Application configuration
session_start();
date_default_timezone_set('Africa/Johannesburg');

// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'vertexcg_vsmxxps');
define('DB_PASSWORD', 'Arj1V54qWWSbChSN1zY*');
define('DB_NAME', 'vertexcg_VSM_data');

// Application settings
define('APP_NAME', 'Vertex Smart Meters');
define('APP_VERSION', '1.0.0');

// Error reporting (turn off in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// URLs
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/production_webapp/');
?>
