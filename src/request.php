<?php

namespace Sunnyvale\REST;

class Request
{
    public $method;
    public $path = null;
    public $resource = null;
    public $resourceId = null;
    public $subresource = null;
    public $subresourceId = null;
    public $body = null;

    public function __construct($server)
    {
        $this->method = $server["REQUEST_METHOD"];
        $uri = $server["REQUEST_URI"];
        $this->parseRequestUri($uri);
        $this->body = json_decode(file_get_contents("php://input"), true);
    }

    private function parseRequestUri($uri)
    {
        $parts = explode("/", $uri);
        array_shift($parts); // remove first empty part, becooz

        for ($i = 0; $i < count($parts); $i++) {
            switch ($i) {
                case 0:
                {
                    $this->resource = $parts[0];
                    $this->path = $parts[0];
                    break;
                }
                case 1:
                {
                    $this->resourceId = $parts[1];
                    break;
                }
                case 2:
                {
                    $this->subresource = $parts[2];
                    $this->path = $parts[0] . "/" . $parts[2];
                    break;
                }
                case 3:
                {
                    $this->subresourceId = $parts[3];
                    break;
                }
            }
        }
    }
}
