<?php

require_once "core/request.php";

use PHPUnit\Framework\TestCase;

final class Request_Test extends TestCase
{
	/**
	 * @dataProvider provider
	 */
	public function testRequest($server, $expected)
	{
		$request = new Request($server);
		$this->assertEquals($expected["method"], $request->method);
		$this->assertEquals($expected["resource"], $request->resource);
		$this->assertEquals($expected["resourceId"], $request->resourceId);
		$this->assertEquals($expected["body"], $request->body);
	}

	/**
	 * $server as in $_SERVER mock
	 */
	public function provider()
	{
		$server1 = [
			"REQUEST_METHOD" => "GET",
			"REQUEST_URI" => "/examples"
		];

		$expected1 = [
			"method" => "GET",
			"resource" => "examples",
			"resourceId" => null,
			"body" => null
		];

		$server2 = [
			"REQUEST_METHOD" => "DELETE",
			"REQUEST_URI" => "/examples/10"
		];

		$expected2 = [
			"method" => "DELETE",
			"resource" => "examples",
			"resourceId" => 10,
			"body" => null
		];

		$server3 = [
			"REQUEST_METHOD" => "POST",
			"REQUEST_URI" => "/examples/10/items"
		];

		$expected3 = [
			"method" => "POST",
			"resource" => "examples",
			"resourceId" => 10,
			"subresource" => "items",
			"body" => null
		];

		$server4 = [
			"REQUEST_METHOD" => "PUT",
			"REQUEST_URI" => "/examples/10/items/4"
		];

		$expected4 = [
			"method" => "PUT",
			"resource" => "examples",
			"resourceId" => 10,
			"subresource" => "items",
			"subresourceId" => 4,
			"body" => null
		];

		return [
			[$server1, $expected1],
			[$server2, $expected2],
			[$server3, $expected3],
			[$server4, $expected4]
		];
	}
}
