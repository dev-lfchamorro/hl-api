<?php
require_once dirname(__FILE__) . '../../../../helpers/parse-api-request.php';

$action = parseApiRequest('v1', 'contacts/');

switch ($action) {
    case 'get-contacts':
        include "$action.php";
        break;

    default:
        http_response_code(404);
        echo json_encode(array("status" => "error", "message" => "Invalid action"));
        break;
}
