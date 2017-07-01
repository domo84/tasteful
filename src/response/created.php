<?php

namespace Sunnyvale\REST\Response;

use Sunnyvale\REST\Response;

class Created extends Response
{
    public $code = 201;

    public function __construct($path)
    {
        $location = "http://" . $_SERVER["HTTP_HOST"] . $path;
        $this->headers[] = "Location: $location";
    }
}
