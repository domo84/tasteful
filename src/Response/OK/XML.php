<?php

namespace Sunnyvale\REST\Response\OK;

use Sunnyvale\REST\Response\OK;

class XML extends OK
{
    public $code = 200;

    public function __construct($body)
    {
        $this->body = $body;
        $this->headers[] = "Content-Type: text/xml; charset=utf-8";
    }
}
