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

    $name = $data['name'];
    $phone = $data['phone'];
    $email = $data['email'];
    $message = $data['message'];

    $sql = "INSERT INTO contacts (name, phone, email, message) VALUES ('$name', '$phone', '$email', '$message')";
    $result = $conn->query($sql);

    if ($result) {
        $id = $conn->insert_id;
        getContactById($conn, $id);
    } else {
        echo json_encode(array("status" => "error", "message" => "Error executing INSERT query"));
    }
}
