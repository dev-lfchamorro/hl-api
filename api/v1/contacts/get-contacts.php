<?php
require_once dirname(__FILE__) . '../../../../config/database.php';
require_once dirname(__FILE__) . '../../../../helpers/parse-api-request.php';

header('Content-Type: application/json');

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
