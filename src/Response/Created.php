<?php

namespace Tasteful\Response;

use Tasteful\Response;

class Created extends Response
{
    public $code = 201;

    public function __construct($path)
    {
        $location = "http://" . $_SERVER["HTTP_HOST"] . $path;
        $this->headers[] = "Location: $location";
    }
}
