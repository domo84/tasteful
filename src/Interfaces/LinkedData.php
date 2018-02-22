<?php

namespace Sunnyvale\REST\Interfaces;

use Sunnyvale\REST\Response;

/**
 * @description Implemented by resources to support transformation of JSON to LinkedData+JSON
 */
interface LinkedData
{
    public function toLinkedData(Response\OK\JSON $response): Response\OK\LinkedData;
}
