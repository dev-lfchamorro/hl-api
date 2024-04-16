<?php
require_once dirname(__FILE__) . '../../../../config/database.php';

header('Content-Type: application/json');

$request_uri = $_SERVER['REQUEST_URI'];
$api_version = 'v1';

$api_request = str_replace("/hl-api/api/$api_version/contacts/get-contacts/", '', $request_uri);
$api_parts = explode('/', $api_request);

$id = isset($api_parts[0]) ? $api_parts[0] : null;

if ($id && is_numeric($id)) {
    getContactByID($conn, $id);
} else {
    getAllContacts($conn);
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

    $conn->close();
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

    $conn->close();
}
