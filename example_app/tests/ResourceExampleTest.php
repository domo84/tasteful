<?php

namespace Sunnyexample\TEST;

use PHPUnit\Framework\TestCase;

final class ResourceExampleTest extends TestCase
{
    use provider;

    private $rest_client = null;

    public function setUp()
    {
        $this->rest_client = new Rest_Client();
    }

    /**
     * Basically only for setting up data
     * @dataProvider examples
     */
    public function testPost($example)
    {
        list($result, $code) = $this->rest_client->post("examples", $example);
        $this->assertEquals($example, $result);
        $this->assertEquals(200, $code);
    }

    /**
     * @depends testPut
     * @dataProvider examples
     */
    public function testGetOne($example)
    {
        $id = $example["id"];
        list($result, $code) = $this->rest_client->get("examples/$id");
        $this->assertEquals($example, $result);
        $this->assertEquals(200, $code);
    }

    /**
     * @dataProvider moreExamples
     */
    public function testPut($example)
    {
        $id = $example["id"];
        list($result, $code) = $this->rest_client->put("examples/$id", $example);
        $this->assertEquals($example, $result);
        $this->assertEquals(200, $code);
    }

    /**
     * @dataProvider examples
     */
    public function testPutNonExistent($example)
    {
        list($result, $code) = $this->rest_client->put("example/512", $example);
        $this->assertNull($result);
        $this->assertEquals(404, $code);
    }

    public function testDeleteNonExistent()
    {
        list($result, $code) = $this->rest_client->delete("doesnotexists/100");
        $this->assertNull($result);
        $this->assertEquals(404, $code);
    }

    /**
     * @dataProvider faultyExamples
     */
    public function testNotFound($example)
    {
        $id = $example["id"];
        list($result, $code) = $this->rest_client->get("examples/$id");
        $this->assertNull($result);
        $this->assertEquals(404, $code);
    }

    public function tearDown()
    {
        $path = __DIR__ . "/../storage/services.db";
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
