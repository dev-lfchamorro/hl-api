<?php
header('Content-Type: application/json');

require_once dirname(__FILE__) . '../../../../config/database.php';
require_once dirname(__FILE__) . '../../../../helpers/parse-api-request.php';
require_once dirname(__FILE__) . '../../../../helpers/detect-request-valid-method.php';

detectRequestValidMethod('GET');

$id = parseApiRequest('contacts/get-contacts/');

if ($id && is_numeric($id)) {
    getContactByID($conn, $id);
} else {
    getAllContacts($conn);
}

function getAllContacts($conn)
{
    $sql = "SELECT * FROM contacts";
    $result = $conn->query($sql);

    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    }

    echo json_encode($data);

    $conn->close();
}

function getContactById($conn, $id)
{
    $sql = "SELECT * FROM contacts WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("status" => "error", "message" => "Error preparing SQL statement"));
        return;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }

        echo json_encode($data);
    } else {
        echo json_encode(array("status" => "error", "message" => "Contact not found"));
    }

    $stmt->close();
    $conn->close();
}
