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

switch ($method) {
    case 'GET':
        getContacts($conn);
        break;

    case 'POST':
        echo "Request - POST";
        break;

    case 'PUT':
        echo "Request - PUT";
        break;

    case 'DELETE':
        echo "Request - DELETE";
        break;

    default:
        echo json_encode(array("status" => "error"));
        break;
}

function getContacts($conn)
{
    $sql = "SELECT * FROM contacts";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    }

    echo json_encode($data);
}
