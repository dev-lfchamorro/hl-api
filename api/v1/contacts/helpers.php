<?php

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

    if ($result && $result->num_rows > 0) {
        $contact = $result->fetch_assoc();
        echo json_encode(array("status" => "success", "contact" => $contact));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error retrieving contact"));
    }

    $stmt->close();
    $conn->close();
}
