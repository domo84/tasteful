<?php

namespace Tasteful\Response\OK;

use Tasteful\Response\OK;

class XML extends OK
{
    public $code = 200;

    public function __construct($body)
    {
        $this->body = $body;
        $this->headers[] = "Content-Type: text/xml; charset=utf-8";
    }
}
