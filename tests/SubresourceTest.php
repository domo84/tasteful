<?php

require_once "core/subresource.php";
require_once "core/request.php";
require_once "core/exceptions.php";

use PHPUnit\Framework\TestCase;

final class Example_Items extends Subresource\Many
{}

final class Example_Item extends Subresource\One
{}

final class Subesource_Test extends TestCase
{
	/**
	 * @dataProvider provider
	 */
	public function testFind($req, $ex)
	{
		$resource = Subresource\Subresource::find($req);
		$this->assertInstanceOf($ex, $resource);
	}

	/**
	 * @expectedException Exception\Subresource\NotFound
	 */
	public function testNotFound()
	{
		$req = new Request(["REQUEST_METHOD" => "GET", "REQUEST_URI" => "/examples/10/doesnotexist"]);
		Subresource\Subresource::find($req);
	}

	/**
	 * @expectedException Exception\Subresource\Verb\NotImplemented
	 * @dataProvider provider
	 */
	public function testNotImplemented($req)
	{
		$res = Subresource\Subresource::find($req);
		$res->run($req);
	}

	/**
	 * @expectedException Exception\Subresource\Verb\NotSupported
	 */
	public function testMethodNotSupported()
	{
		$req = new Request(["REQUEST_METHOD" => "H4X", "REQUEST_URI" => "/examples/10/items"]);
		$res = Subresource\Subresource::find($req);
		$res->run($req);
	}

	public function provider()
	{
		$req1 = new Request(["REQUEST_METHOD" => "GET", "REQUEST_URI" => "/examples/10/items"]);
		$req2 = new Request(["REQUEST_METHOD" => "DELETE", "REQUEST_URI" => "/examples/10/items/4"]);

		return [
			[$req1, Example_Items::class],
			[$req2, Example_Item::class]
		];
	}
}
