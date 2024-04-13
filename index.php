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
        createContact($conn);
        break;

    case 'PUT':
        updateContact($conn);
        break;

    case 'DELETE':
        deleteContact($conn);
        break;

    default:
        echo json_encode(array("status" => "error", "message" => "Invalid Method"));
        break;
}

function getContacts($conn)
{
    $id = getIdByURL();

    if ($id) {
        getContactByID($conn, $id);
    } else {
        getAllContacts($conn);
    }
}

function getAllContacts($conn)
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

function getContactById($conn, $id)
{
    $sql = "SELECT * FROM contacts WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }

        echo json_encode($data);
    } else {
        echo json_encode(array("status" => "error", "message" => "Contact not found"));
    }
}

function createContact($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    $name = $data['name'];
    $phone = $data['phone'];
    $email = $data['email'];
    $message = $data['message'];

    $sql = "INSERT INTO contacts (name, phone, email, message) VALUES ('$name', '$phone', '$email', '$message')";
    $result = $conn->query($sql);

    if ($result) {
        echo json_encode($conn->insert_id);
    } else {
        echo json_encode(array("status" => "error", "message" => "Error executing INSERT query"));
    }
}

function updateContact($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'];
    $name = $data['name'];
    $phone = $data['phone'];
    $email = $data['email'];
    $message = $data['message'];

    if (!$id) {
        http_response_code(400); // Bad Request
        die(json_encode(array("status" => "error", "message" => "ID is required")));
    }


    $sql = "UPDATE contacts SET name = '$name', phone = '$phone', email = '$email', message = '$message' WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result) {
        echo json_encode(array("status" => "success", "message" => "Contact successfully updated"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error executing UPDATE query"));
    }
}

function deleteContact($conn)
{
    $id = getIdByURL();

    if (!$id) {
        http_response_code(400); // Bad Request
        die(json_encode(array("status" => "error", "message" => "ID is required")));
    }

    $sql = "DELETE FROM contacts WHERE id = $id";
    $result = $conn->query($sql);

    if ($result) {
        echo json_encode(array("status" => "success", "message" => "Contact deleted successfully"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error executing DELETE query"));
    }
}

function getIdByURL()
{
    $path = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $pathSplit = explode('/', $path);
    $id = $path !== '/' ? end($pathSplit) : null;

    return $id;
}
