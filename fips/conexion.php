
<?php
$host = "212.1.208.199";
$dbname = "u312507976_db61";
$username = "u312507976_db61";
$password = "4Ag1224-1";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>