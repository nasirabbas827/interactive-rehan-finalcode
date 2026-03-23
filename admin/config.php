<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db   = 'entrepreneur_portal-rehan';
$user = 'root';
$pass = '';            

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set UTF-8
mysqli_set_charset($conn, "utf8mb4");
?>