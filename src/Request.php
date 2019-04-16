<?php

namespace Tasteful;

class Request
{
    public $headers = [];
    public $method;
    public $path = null;
    public $resource = null;
    public $resourceId = null;
    public $subresource = null;
    public $subresourceId = null;
    public $body = null;
    public $token = null;
    public $params = array();

    public function __construct($server)
    {
        $this->method = $server["REQUEST_METHOD"];
        $uri = $server["REQUEST_URI"];
        $this->parseRequestUri($uri);

        if (isset($server["HTTP_ACCEPT"])) {
            $this->headers["accept"] = $server["HTTP_ACCEPT"];
        }

        if (isset($server["HTTP_AUTHORIZATION"])) {
            $this->token = str_replace("token ", "", $server["HTTP_AUTHORIZATION"]);
        }

        if (isset($server["_BODY"])) {
            $this->body = json_decode($server["_BODY"], true);
        } else {
            $this->body = json_decode(file_get_contents("php://input"), true);
        }

        if (isset($server["_GET"])) {
            $this->params = $server["_GET"];
        } else {
            $this->params = $_GET;
        }
    }

    private function parseRequestUri($uri)
    {
        $path = preg_replace('/\?.*/', '', $uri);
        $parts = explode("/", $path);
        array_shift($parts); // remove first empty part, becooz

        for ($i = 0; $i < count($parts); $i++) {
            switch ($i) {
                case 0:
                    $this->resource = $parts[0];
                    $this->path = $parts[0];
                    break;
                case 1:
                    $this->resourceId = $parts[1];
                    break;
                case 2:
                    $this->subresource = $parts[2];
                    $this->path = $parts[0] . "/" . $parts[2];
                    break;
                case 3:
                    $this->subresourceId = $parts[3];
                    break;
            }
        }
    }
}
