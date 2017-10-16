<?php

namespace Sunnyexample\TEST;

use PHPUnit\Framework\TestCase;

final class ResourceExampleItemsTest extends TestCase
{
    use provider;

    private $rest_client = null;

    public function setUp()
    {
        $this->rest_client = new Rest_Client();
    }

    public function testInsert()
    {
        $values = ["id" => 1, "name" => "Name#1", "content" => "Content#1"];
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
        $id = $items["id"];
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

    public function tearDown()
    {
        $path = __DIR__ . "/../storage/services.db";
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
