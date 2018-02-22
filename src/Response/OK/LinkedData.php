<?php

namespace Sunnyvale\REST\Response\OK;

use Sunnyvale\REST\Response\OK;
use Sunnyvale\REST\Traits\JSONTools;

class LinkedData extends OK
{
    use JSONTools;

    public function __construct($body)
    {
        $body = $this->isJSON($body) ? $body : $this->encode($body);
        $body .= "\n\r";

        $this->body = $body;

        $this->headers[] = "Content-Type: application/ld+json; charset=utf-8";
        $this->headers[] = "Content-Length: " . strlen($this->body);
    }
}
