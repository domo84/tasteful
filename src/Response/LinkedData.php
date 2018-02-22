<?php

namespace Sunnyvale\REST\Response;

use Sunnyvale\REST\Response;

class LinkedData extends JSON
{
    public function __construct($body)
    {
        $this->headers[] = "Content-Type: application/ld+json; charset=utf-8";
    }
}
