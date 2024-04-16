<?php
require_once dirname(__FILE__) . '../../../helpers/parse-api-request.php';

$resource = parseApiRequest('v1');

switch ($resource) {
    case 'contacts':
        include "./$resource/index.php";
        break;

    default:
        http_response_code(404);
        echo json_encode(array("status" => "error", "message" => "Invalid resource"));
        break;
}
