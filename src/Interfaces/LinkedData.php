<?php

namespace Tasteful\Interfaces;

use Tasteful\Response;

/**
 * @description Implemented by resources to support transformation of JSON to LinkedData+JSON
 */
interface LinkedData
{
    public function toLinkedData(Response\OK\JSON $response): Response\OK\LinkedData;
}
