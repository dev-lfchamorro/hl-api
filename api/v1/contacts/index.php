<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

$request_uri = $_SERVER['REQUEST_URI'];

if (basename($request_uri) === 'contacts') {
    http_response_code(400);
    die(json_encode(array("status" => "error", "message" => "Bad request, invalid action")));
}

require_once dirname(__FILE__) . '../../../../helpers/parse-api-request.php';

$action = parseApiRequest('contacts/');

switch ($action) {
    case 'get-contacts':
        include "$action.php";
        break;

    case 'create-contact':
        include "$action.php";
        break;

    case 'update-contact':
        include "$action.php";
        break;

    case 'delete-contact':
        include "$action.php";
        break;

    default:
        http_response_code(404);
        echo json_encode(array("status" => "error", "message" => "Invalid action"));
        break;
}
