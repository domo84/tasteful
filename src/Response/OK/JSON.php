<?php

namespace Tasteful\Response\OK;

use Tasteful\Response\OK;
use Tasteful\Traits\JSONTools;

class JSON extends OK
{
    use JSONTools;

    public $decoded;

    public function __construct($body)
    {
        $this->decoded = $body; # Saved for ld+json purposes

        $body = $this->isJSON($body) ? $body : $this->encode($body);
        $body .= "\n\r";

        $this->body = $body;

        $this->headers[] = "Content-Type: application/json; charset=utf-8";
    }
}
