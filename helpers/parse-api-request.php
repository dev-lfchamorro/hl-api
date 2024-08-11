<?php
function parseApiRequest($segment = null)
{
    require_once '../../config/config.php';

    $request_uri = $_SERVER['REQUEST_URI'];
    $api_request = str_replace(API_PATH . "/api/" . API_VERSION . "/$segment", '', $request_uri);
    $api_parts = explode('/', $api_request);

    $ulr_resource = isset($api_parts[0]) ? $api_parts[0] : null;

    return $ulr_resource;
}
