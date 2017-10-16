<?php

namespace Sunnyexample\TEST;

use PHPUnit\Framework\TestCase;

final class ResourceExampleItemTest extends TestCase
{
    use provider;

    private $rest_client = null;

    public function setUp()
    {
        $this->rest_client = new Rest_Client();
    }

    /**
     * Only for setting up test data
     */
    public function testInsert()
    {
        $values = ["name" => "Name#1", "content" => "Content#1"];
        list($result, $code) = $this->rest_client->post("examples", $values);
        unset($result["id"]);
        $this->assertEquals(200, $code);
        $this->assertEquals($values, $result);
    }

    /**
     * @dataProvider items
     */
    public function testInsertItems($item)
    {
        list($result, $code) = $this->rest_client->post("examples/1/items", $item);
        $this->assertEquals($item, $result);
        $this->assertEquals(200, $code);
    }

    /**
     * @dataProvider items
     */
    public function testGetOne($items)
    {
        $id = $items["id"];
        list($result, $code) = $this->rest_client->get("examples/1/items/$id");
        $this->assertEquals($items, $result);
        $this->assertEquals(200, $code);
    }

    /**
     * @dataProvider moreItems
     */
    public function testPut($item)
    {
        $id = $item["id"];
        list($result, $code) = $this->rest_client->put("examples/1/items/$id", $item);
        $this->assertEquals($item, $result);
        $this->assertEquals(200, $code);
    }

    /**
     * @dataProvider items
     */
    public function testPutNonExistent($item)
    {
        list($result, $code) = $this->rest_client->put("examples/1/items/911", $item);
        $this->assertNull($result);
        $this->assertEquals(404, $code);
    }

    public function testDeleteNonExistent()
    {
        list($result, $code) = $this->rest_client->delete("examples/1/items/100");
        $this->assertNull($result);
        $this->assertEquals(204, $code);
    }

    /**
     * @dataProvider faultyItems
     */
    public function testNotFound($item)
    {
        $id = $item["id"];
        list($result, $code) = $this->rest_client->get("examples/1/items/$id");
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
