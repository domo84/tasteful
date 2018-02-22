<?php

namespace Sunnyvale\REST\Response\OK;

use Sunnyvale\REST\Response\OK;
use Sunnyvale\REST\Traits\JSONTools;

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
        $this->headers[] = "Content-Length: " . strlen($this->body);
    }
}
