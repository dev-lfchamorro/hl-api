<?php
function detectRequestValidMethod($method = null)
{
    $request_method = $_SERVER['REQUEST_METHOD'];

    if ($request_method !== $method) {
        http_response_code(405);
        die(json_encode(array("status" => "error", "message" => "Method not allowed")));
    }
}
