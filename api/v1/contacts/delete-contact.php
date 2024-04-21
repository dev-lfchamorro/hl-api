<?php
header('Content-Type: application/json');

require_once dirname(__FILE__) . '../../../../config/database.php';
require_once dirname(__FILE__) . '../../../../helpers/parse-api-request.php';
require_once dirname(__FILE__) . '../../../../helpers/detect-request-valid-method.php';

detectRequestValidMethod('DELETE');

$id = parseApiRequest('contacts/delete-contact/');

if ($id && !is_numeric($id)) {
    http_response_code(400);
    die(json_encode(array("status" => "error", "message" => "ID is required and must be a number")));
}

deleteContact($conn, $id);

function deleteContact($conn, $id)
{
    if (!$id) {
        http_response_code(400); // Bad Request
        die(json_encode(array("status" => "error", "message" => "ID is required")));
    }

    $sql = "DELETE FROM contacts WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        die(json_encode(array("status" => "error", "message" => "Error preparing SQL statement")));
    }

    $stmt->bind_param("i", $id);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(array("status" => "success", "message" => "Contact deleted successfully"));
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("status" => "error", "message" => "Error executing DELETE query"));
    }

    $stmt->close();
}
