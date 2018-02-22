<?php

namespace Sunnyvale\REST\Response\OK\JSON;

use Sunnyvale\REST\Response\OK\JSON;

class LinkedData extends JSON
{
    public function __construct($body)
    {
        $this->headers[] = "Content-Type: application/ld+json; charset=utf-8";
    }
}
