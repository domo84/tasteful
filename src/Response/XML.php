<?php

namespace Sunnyvale\REST\Response;

use Sunnyvale\REST\Response;

class XML extends Response
{
    public $code = 200;

    public function __construct($body)
    {
        $this->body = $body;
        $this->headers[] = "Content-Type: text/xml; charset=utf-8";
    }
}
