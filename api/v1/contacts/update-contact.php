<?php
header('Content-Type: application/json');

require_once dirname(__FILE__) . '../../../../config/database.php';
require_once dirname(__FILE__) . '../../../../helpers/detect-request-valid-method.php';
require_once 'helpers.php';

detectRequestValidMethod('PUT');

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

    $params = array();
    $types = '';

    if (isset($data['name'])) {
        $name = $data['name'];
        $update_fields[] = "name = ?";
        $params[] = &$name;
        $types .= 's'; // 's' (string)
    }

    if (isset($data['phone'])) {
        $phone = $data['phone'];
        $update_fields[] = "phone = ?";
        $params[] = &$phone;
        $types .= 's';
    }

    if (isset($data['email'])) {
        $email = $data['email'];
        $update_fields[] = "email = ?";
        $params[] = &$email;
        $types .= 's';
    }

    if (isset($data['message'])) {
        $message = $data['message'];
        $update_fields[] = "message = ?";
        $params[] = &$message;
        $types .= 's';
    }

    if (empty($update_fields)) {
        http_response_code(400); // Bad Request
        die(json_encode(array("status" => "error", "message" => "No fields to update")));
    }

    $sql = "UPDATE contacts SET " . implode(", ", $update_fields) . " WHERE id = ?";
    $types .= 'i'; // 'i' (integer);
    $params[] = &$id;

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        die(json_encode(array("status" => "error", "message" => "Error preparing SQL statement")));
    }

    if (!empty($params)) {
        $bind_params = array_merge(array($types), $params);
        call_user_func_array(array($stmt, 'bind_param'), $bind_params);
    }

    $result = $stmt->execute();

    if ($result) {
        getContactById($conn, $id);
    } else {
        echo json_encode(array("status" => "error", "message" => "Error executing UPDATE query"));
    }

    $conn->close();
}
