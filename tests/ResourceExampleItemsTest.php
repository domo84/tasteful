<?php

require_once "lib/rest_client.php";
require_once "lib/traits.php";

use PHPUnit\Framework\TestCase;

final class Resource_Example_Items_Test extends TestCase
{
    use provider;

    private $rest_client = null;

    public function setUp()
    {
        $this->rest_client = new Rest_Client();
    }

    public function testInsert()
    {
        $values = ["_id" => 1, "name" => "Name#1", "content" => "Content#1"];
        list($result, $code) = $this->rest_client->post("examples", $values);
        $this->assertEquals($values, $result);
    }

    /**
     * @dataProvider items
     */
    public function testPost($item)
    {
        list($result, $code) = $this->rest_client->post("examples/1/items", $item);
        $this->assertEquals(200, $code);
        $this->assertEquals($item, $result);
    }

    /**
     * @dataProvider items
     */
    public function testDelete($items)
    {
        $id = $items["_id"];
        list($result, $code) = $this->rest_client->delete("examples/1/items/$id");
        $this->assertNull($result);
        $this->assertEquals(204, $code);
    }

    public function testDeleteResource()
    {
        list($result, $code) = $this->rest_client->delete("examples/1/items");
        $this->assertNull($result);
        $this->assertEquals(501, $code);
    }

    public function testDeleteNonExistentResource()
    {
        list($result, $code) = $this->rest_client->delete("examples/1/doesnotexists");
        $this->assertNull($result);
        $this->assertEquals(404, $code);
    }

    public function testGet()
    {
        list($result, $code) = $this->rest_client->get("examples/1/items");
        $this->assertEquals(200, $code);
        $this->assertEquals(0, count($result));
    }

    public static function tearDownAfterClass()
    {
        unlink("data/services.db");
    }
}
