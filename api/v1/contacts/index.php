<?php

header('Content-Type: application/json');

$request_uri = $_SERVER['REQUEST_URI'];
$api_version = 'v1';

$api_request = str_replace("/hl-api/api/$api_version/contacts/", '', $request_uri);
$api_parts = explode('/', $api_request);

$action = isset($api_parts[0]) ? $api_parts[0] : null;

switch ($action) {
    case 'get-contacts':
        include "$action.php";
        break;

    default:
        http_response_code(404);
        echo json_encode(array("status" => "error", "message" => "Invalid action"));
        break;
}
