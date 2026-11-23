
<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "childdb";
$port = 3307;

$conn = mysqli_connect(
    $servername,
    $username,
    $password,
    $database,
    $port
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}



?>
