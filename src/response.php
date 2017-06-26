<?php

namespace Sunnyvale\REST\Response;

abstract class Response
{
    public $code = null;
    public $body = null;
    public $headers = array(
        "Access-Control-Allow-Origin: *",
        "Access-Control-Allow-Methods: DELETE, GET, HEAD, OPTIONS, POST, PUT",
        "Access-Control-Allow-Headers: Content-Type"
    );
}

// @codingStandardsIgnoreLine
class Options extends Response
{
    public $code = 200;
}

// @codingStandardsIgnoreLine
class Conflict extends Response
{
    public $code = 409;
}

// @codingStandardsIgnoreLine
class JSON extends Response
{
    public $code = 200;

    public function __construct($body)
    {
        $this->body = $this->isJSON($body) ? $body . "\n\r" : json_encode($body, JSON_UNESCAPED_UNICODE) . "\n\r";
        $this->headers[] = "Content-Type: application/json; charset=utf-8";
        $this->headers[] = "Content-Length: " . strlen($this->body);
    }

    private function isJSON($content)
    {
        if (is_array($content)) {
            return false;
        }

        $pos = strpos($content, "{");
        if ($pos == 1 || $pos == 2) {
            return true;
        }
        return false;
    }
}

// @codingStandardsIgnoreLine
class NotFound extends Response
{
    public $code = 404;
}

// @codingStandardsIgnoreLine
class Created extends Response
{
    public $code = 201;

    public function __construct($path)
    {
        $location = "http://" . $_SERVER["HTTP_HOST"] . $path;
        $this->headers[] = "Location: $location";
    }
}

// @codingStandardsIgnoreLine
class NoContent extends Response
{
    public $code = 204;
}
