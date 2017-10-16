<?php

namespace Sunnyexample\TEST;

use PHPUnit\Framework\TestCase;

use Sunnyexample\TEST\Traits\Provider;

final class ExamplesTest extends TestCase
{
    use Provider;

    private $client;

    public function setUp()
    {
        $this->client = new Rest_Client();
    }

    /**
     * @dataProvider examples
     */
    public function testInsert($values)
    {
        list($result, $code) = $this->client->post("examples", $values);
        $this->assertEquals(200, $code);
    }

    /**
     * @depends testInsert
     */
    public function testGet()
    {
        list($result, $code) = $this->client->get("examples");
        $this->assertEquals(200, $code);
        $this->assertCount(2, $result);
    }

    /**
     * @depends testInsert
     * @dataProvider examples
     */
    public function testGetOne($values)
    {
        $id = $values["id"];
        list($result, $code) = $this->client->get("examples/$id");
        $this->assertEquals(200, $code);
        $this->assertEquals($values, $result);
    }

    /**
     * @depends testGetOne
     * @dataProvider moreExamples
     */
    public function testPut($values)
    {
        $id = $values["id"];
        list($result, $code) = $this->client->put("examples/$id", $values);
        $this->assertEquals(200, $code);
        $this->assertEquals($values, $result);
    }

    /**
     * @depends testGetOne
     * @dataProvider examples
     */
    public function testDelete($values)
    {
        $id = $values["id"];
        list($result, $code) = $this->client->delete("examples/$id");
        $this->assertEquals(204, $code);
        $this->assertNull($result);
    }

    /**
     * @depends testDelete
     * @dataProvider examples
     */
    public function testNotFound($values)
    {
        $id = $values["id"];
        list($result, $code) = $this->client->get("examples/$id");
        $this->assertEquals(404, $code);
        $this->assertNull($result);
    }

    /**
     * @depends testDelete
     */
    public function testGetAllEmpty()
    {
        list($result, $code) = $this->client->get("examples");
        $this->assertEquals(200, $code);
        $this->assertCount(0, $result);
    }

    public static function tearDownAfterClass()
    {
        $path = __DIR__ . "/../../storage/services.db";
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
