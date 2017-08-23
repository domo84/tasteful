<?php

namespace Sunnyvale\REST;

abstract class Response
{
    public $code = null;
    public $body = null;
    public $headers = array(
        "Access-Control-Allow-Origin: *",
        "Access-Control-Allow-Methods: DELETE, GET, HEAD, OPTIONS, POST, PUT",
        "Access-Control-Allow-Headers: Authorization, Content-Type"
    );
}
