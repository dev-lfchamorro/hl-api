<?php

$host = "localhost";
$user = "root";
$pass = "root";
$db_name = "hl_api";

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}

$conn->set_charset("utf8mb4");

return $conn;
