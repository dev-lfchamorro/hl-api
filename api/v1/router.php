<?php
$request_uri = $_SERVER['REQUEST_URI'];
$api_version = 'v1';

$api_request = str_replace("/hl-api/api/$api_version/", '', $request_uri);
$api_parts = explode('/', $api_request);

$resource = isset($api_parts[0]) ? $api_parts[0] : null;

switch ($resource) {
    case 'contacts':
        include "./$resource/index.php";
        break;

    default:
        http_response_code(404);
        echo json_encode(array("status" => "error", "message" => "Invalid resource"));
        break;
}
