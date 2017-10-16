<?php

namespace Sunnyexample\TEST;

use PHPUnit\Framework\TestCase;

final class ExamplesTest extends TestCase
{
    private $client;

    public function setUp()
    {
        $this->client = new Rest_Client();
    }

    public function testGet()
    {
        list($result, $code) = $this->client->get("examples");
        $this->assertEquals(200, $code);
    }
}
