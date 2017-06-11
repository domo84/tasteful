<?php

require_once "lib/curl_client.php";

use PHPUnit\Framework\TestCase;

/**
 * @covers Examples
 */
final class Resource_Example_Test extends TestCase
{
	public static function setUpBeforeClass()
	{
		if(file_exists("data/services.db"))
		{
			unlink("data/services.db");
		}

		$db = new SQLite3("data/services.db");
		$db->query("CREATE TABLE example (_id INTEGER PRIMARY KEY AUTOINCREMENT, name STRING, content STRING)");
	}

	public static function tearDownAfterClass()
	{
		unlink("data/services.db");
	}

	public function provider()
	{
		return array(array("name" => "Name#1", "content" => "Content#1"));
	}

	public function testGet()
	{
		$curl = new Curl_Client();
		$curl->url = "http://localhost:8080/examples";
		$curl->returntransfer = 1;
		list($result, $code) = $curl->exec();
		var_dump($result);
		$this->assertEquals(0, count($result));
	}

	/**
	 * @depends testGet
	 * @dataProvider provider
	 */
	public function testPost($name, $content)
	{
		$curl = new Curl_Client();
		$curl->url = "http://localhost:8080/examples";
		$curl->returntransfer = 1;
		$curl->method = "POST";
		$curl->postfields = array("name" => $name, "content" => $content);

		list($result, $code) = $curl->exec();

		$this->assertEquals(1, $result->_id);
		$this->assertEquals($name, $result->name);
		$this->assertEquals($content, $result->content);
	}

	/**
	 * @depends testPost
	 */
	public function testGetAllAfterPost()
	{
		$curl = new Curl_Client();
		$curl->url = "http://localhost:8080/examples";
		$curl->returntransfer = 1;
		list($result, $code) = $curl->exec();
		$this->assertEquals(count($result), 1);
	}

	/**
	 * @depends testPost
	 * @dataProvider provider
	 */
	public function testGetOneAfterPost($name, $content)
	{
		$curl = new Curl_Client();
		$curl->url = "http://localhost:8080/examples/1";
		$curl->returntransfer = 1;
		list($result, $code) = $curl->exec();

		$this->assertEquals(1, $result->_id);
		$this->assertEquals($name, $result->name);
		$this->assertEquals($content, $result->content);
	}

	/**
	 * @depends testGetOneAfterPost
	 */
	public function testPut()
	{
		$curl = new Curl_Client();
		$curl->url = "http://localhost:8080/examples/1";
		$curl->method = "PUT";
		$curl->returntransfer = 1;
		$curl->postfields = array("name" => "name", "content" => "content");
		list($result, $code) = $curl->exec();

		$this->assertEquals(1, $result->_id);
		$this->assertEquals("name", $result->name);
		$this->assertEquals("content", $result->content);
	}

	/**
	 * @depends testPut
	 */
	public function testDelete()
	{
		$curl = new Curl_Client();
		$curl->url = "http://localhost:8080/examples/1";
		$curl->method = "DELETE";
		list(, $code) = $curl->exec();
		$this->assertEquals(204, $code);
	}

	/**
	 * @depends testDelete
	 */
	public function testGetAllAfterDelete()
	{
		$curl = new Curl_Client();
		$curl->url = "http://localhost:8080/examples";
		$curl->returntransfer = 1;
		list($result, $code) = $curl->exec();
		$this->assertEquals(0, count($result));
	}

	public function testNotFound()
	{
		$curl = new Curl_Client();
		$curl->url = "http://localhost:8080/examples/10";
		$curl->returntransfer = 1;
		list($result, $code) = $curl->exec();
		$this->assertEquals(404, $code);
	}
}
