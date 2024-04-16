<?php
function parseApiRequest($api_version, $segment = null)
{
    $request_uri = $_SERVER['REQUEST_URI'];
    $api_request = str_replace("/hl-api/api/$api_version/$segment", '', $request_uri);
    $api_parts = explode('/', $api_request);

    $ulr_resource = isset($api_parts[0]) ? $api_parts[0] : null;

    return $ulr_resource;
}
