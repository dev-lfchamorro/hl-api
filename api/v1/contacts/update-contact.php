<?php

require_once dirname(__FILE__) . '../../../../config/database.php';
require_once 'helpers.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'PUT') {
    http_response_code(405);
    die(json_encode(array("status" => "error", "message" => "Method not allowed")));
}

updateContact($conn);

function updateContact($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);
    $update_fields = array();

    $id = $data['id'];

    if (!$id) {
        http_response_code(400); // Bad Request
        die(json_encode(array("status" => "error", "message" => "ID is required")));
    }

    if (isset($data['name'])) {
        $name = $data['name'];
        $update_fields[] = "name = '$name'";
    }

    if (isset($data['phone'])) {
        $phone = $data['phone'];
        $update_fields[] = "phone = '$phone'";
    }

    if (isset($data['email'])) {
        $email = $data['email'];
        $update_fields[] = "email = '$email'";
    }

    if (isset($data['message'])) {
        $message = $data['message'];
        $update_fields[] = "message = '$message'";
    }

    if (empty($update_fields)) {
        http_response_code(400); // Bad Request
        die(json_encode(array("status" => "error", "message" => "No fields to update")));
    }

    $sql = "UPDATE contacts SET " . implode(", ", $update_fields) . " WHERE id = $id";
    $result = $conn->query($sql);

    if ($result) {
        getContactById($conn, $id);
    } else {
        echo json_encode(array("status" => "error", "message" => "Error executing UPDATE query"));
    }

    $conn->close();
}
