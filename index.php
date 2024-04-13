<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db_name = "hl_api";

$conn = new mysqli($host, $user, $pass, $db_name);

// Testing connection
if ($conn->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}

// Setting headers and receiving verbs
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

print_r($method);
