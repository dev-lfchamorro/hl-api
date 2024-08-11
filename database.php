<?php

$host = "hl-hlitoral.mysql.uhserver.com";
$user = "hl_admin";
$pass = "HuFp8gBNaX7*b2E";
$db_name = "hl_hlitoral";

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}

$conn->set_charset("utf8mb4");

return $conn;
