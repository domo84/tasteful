<?php

namespace Sunnyvale\REST;

/**
 * @note Access-Control-Allow-Headers always include, no need to add them:
 * - Accept
 * - Accept-Language
 * - Content-Language
 * - Content-Type
 */
abstract class Response
{
    public $code = null;
    public $body = null;
    public $headers = array(
        "Access-Control-Allow-Origin: *",
        "Access-Control-Allow-Methods: DELETE, GET, HEAD, OPTIONS, POST, PUT",
        "Access-Control-Allow-Headers: Authorization"
    );
}
