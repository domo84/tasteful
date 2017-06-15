<?php

require_once "lib/rest_client.php";
require_once "lib/traits.php";

use PHPUnit\Framework\TestCase;

final class Resource_Example_Item_Test extends TestCase
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
	 * Only for setting up data
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
		$id = $items["_id"];
		list($result, $code) = $this->rest_client->get("examples/1/items/$id");
		$this->assertEquals($items, $result);
		$this->assertEquals(200, $code);
	}

	/**
	 * @dataProvider moreItems
	 */
	public function testPut($item)
	{
		$id = $item["_id"];
		list($result, $code) = $this->rest_client->put("examples/1/items/$id", $item);
		$this->assertEquals($item, $result);
		$this->assertEquals(200, $code);
	}

	/**
	 * @dataProvider items
	 */
	public function testPutNonExistent($item)
	{
		list($result, $code) = $this->rest_client->put("example/1/items/911", $item);
		$this->assertNull($result);
		$this->assertEquals(404, $code);
	}

	public function testDeleteNonExistent()
	{
		list($result, $code) = $this->rest_client->delete("example/1/items/100");
		$this->assertNull($result);
		$this->assertEquals(204, $code);
	}

	/**
	 * @dataProvider faultyItems
	 */
	public function testNotFound($item)
	{
		$id = $item["_id"];
		list($result, $code) = $this->rest_client->get("examples/1/items/$id");
		$this->assertNull($result);
		$this->assertEquals(404, $code);
	}


	public static function tearDownAfterClass()
	{
		unlink("data/services.db");
	}
}
