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

    $sql = "DELETE FROM contacts WHERE id = $id";
    $result = $conn->query($sql);

    if ($result) {
        echo json_encode(array("status" => "success", "message" => "Contact deleted successfully"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error executing DELETE query"));
    }
}
