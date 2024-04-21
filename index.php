<?php

http_response_code(400);
die(json_encode(array("status" => "error", "message" => "Bad request")));
