<?php

namespace Sunnyexample\TEST;

use PHPUnit\Framework\TestCase;

use Sunnyexample\TEST\Traits\Provider;

final class ExampleItemsTest extends TestCase
{
    use Provider;

    private $client;
    private static $id;

    public static function setUpBeforeClass()
    {
        $client = new Rest_Client();
        $values = ["id" => 1, "name" => "Name#1", "content" => "Content#1"];
        list($result, $code) = $client->post("examples", $values);
        self::$id = $result["id"];
    }

    public function setUp()
    {
        $this->client = new Rest_Client();
    }

    public function testGetAllAgain()
    {
        $id = self::$id;
        list($result, $code) = $this->client->get("examples/$id/items");
        $this->assertEquals(200, $code);
        $this->assertEmpty($result);
    }

    public function testGetAll()
    {
        $id = self::$id;
        list($result, $code) = $this->client->get("examples/$id/items");
        list($result, $code) = $this->client->get("examples/$id/items");
        $this->assertEquals(200, $code);
    }

    /**
     * @dataProvider items
     * @depends testGetAll
     */
    public function testInsert($values)
    {
        $id = self::$id;
        list($result, $code) = $this->client->post("examples/$id/items", $values);
        $this->assertEquals(200, $code);
        $this->assertEquals($result, $values);
    }

    /**
     * @depends testInsert
     */
    public function testGet()
    {
        $id = self::$id;
        list($result, $code) = $this->client->get("examples/$id/items");
        $this->assertEquals(200, $code);
        $this->assertCount(2, $result);
    }

    /**
     * @depends testInsert
     * @dataProvider items
     */
    public function testGetOne($values)
    {
        $id = self::$id;
        $itemId = $values["id"];
        list($result, $code) = $this->client->get("examples/$id/items/$itemId");
        $this->assertEquals(200, $code);
        $this->assertEquals($values, $result);
    }

    /**
     * @depends testGetOne
     * @dataProvider moreItems
     */
    public function testPut($values)
    {
        $id = self::$id;
        $itemId = $values["id"];
        list($result, $code) = $this->client->put("examples/$id/items/$itemId", $values);
        $this->assertEquals(200, $code);
        $this->assertEquals($values, $result);
    }

    /**
     * @depends testPut
     * @dataProvider items
     */
    public function testDelete($values)
    {
        $id = self::$id;
        $itemId = $values["id"];
        list($result, $code) = $this->client->delete("examples/$id/items/$itemId");
        $this->assertEquals(204, $code);
        $this->assertNull($result);
    }

    /**
     * @depends testDelete
     * @dataProvider items
     */
    public function testNotFound($values)
    {
        $id = self::$id;
        $itemId = $values["id"];
        list($result, $code) = $this->client->get("examples/$id/items/$itemId");
        $this->assertEquals(404, $code);
        $this->assertNull($result);
    }

    /**
     * @depends testDelete
     */
    public function testGetAllEmpty()
    {
        $id = self::$id;
        list($result, $code) = $this->client->get("examples/$id/items");
        $this->assertEquals(200, $code);
        $this->assertEmpty($result);
    }

    public static function tearDownAfterClass()
    {
        $path = __DIR__ . "/../../storage/services.db";
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
