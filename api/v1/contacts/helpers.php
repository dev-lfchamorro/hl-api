<?php

function getContactById($conn, $id)
{
    $sql = "SELECT * FROM contacts WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $contact = $result->fetch_assoc();
        echo json_encode(array("status" => "success", "contact" => $contact));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error retrieving inserted contact"));
    }

    $conn->close();
}
