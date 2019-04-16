<?php

namespace Tasteful\Response\OK;

use Tasteful\Response\OK;
use Tasteful\Traits\JSONTools;

class LinkedData extends OK
{
    use JSONTools;

    public function __construct($body)
    {
        $body = $this->isJSON($body) ? $body : $this->encode($body);
        $body .= "\n\r";

        $this->body = $body;

        $this->headers[] = "Content-Type: application/ld+json; charset=utf-8";
    }
}
