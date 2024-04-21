<?php
header('Content-Type: application/json');

require_once dirname(__FILE__) . '../../../../config/database.php';
require_once dirname(__FILE__) . '../../../../helpers/detect-request-valid-method.php';
require_once 'helpers.php';

detectRequestValidMethod('POST');

createContact($conn);

function createContact($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name']) || !isset($data['email']) || !isset($data['message'])) {
        http_response_code(400); // Bad Request
        echo json_encode(array("status" => "error", "message" => "Name, Email and Message are required"));
        return;
    }

    $name = $data['name'];
    $phone = $data['phone'];
    $email = $data['email'];
    $message = $data['message'];

    $sql = "INSERT INTO contacts (name, phone, email, message) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("status" => "error", "message" => "Error preparing SQL statement"));
        return;
    }

    $stmt->bind_param("ssss", $name, $phone, $email, $message);
    $result = $stmt->execute();

    if ($result) {
        $id = $conn->insert_id;
        getContactById($conn, $id);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("status" => "error", "message" => "Error executing INSERT query"));
    }

    $stmt->close();
}
