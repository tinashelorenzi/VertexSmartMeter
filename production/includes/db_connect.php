require_once 'config.php';

// Create database connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set timezone for MySQL
$conn->query("SET time_zone = '+02:00'");
?>
