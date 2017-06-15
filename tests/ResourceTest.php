<?php

require_once "core/resource.php";
require_once "core/request.php";
require_once "core/exceptions.php";

use PHPUnit\Framework\TestCase;

final class Examples extends Resource\Many
{}

final class Example extends Resource\One
{}

final class Resource_Test extends TestCase
{
	/**
	 * @dataProvider provider
	 */
	public function testFind($req, $ex)
	{
		$resource = Resource\Resource::find($req);
		$this->assertInstanceOf($ex, $resource);
	}

	/**
	 * @expectedException Exception\Resource\NotFound
	 */
	public function testNotFound()
	{
		$req = new Request(["REQUEST_METHOD" => "GET", "REQUEST_URI" => "/doesnotexist"]);
		Resource\Resource::find($req);
	}

	/**
	 * @expectedException Exception\Resource\Verb\NotImplemented
	 * @dataProvider provider
	 */
	public function testNotImplemented($req)
	{
		$res = Resource\Resource::find($req);
		$res->run($req);
	}

	/**
	 * @expectedException Exception\Resource\Verb\NotSupported
	 */
	public function testMethodNotSupported()
	{
		$req = new Request(["REQUEST_METHOD" => "H4X", "REQUEST_URI" => "/examples"]);
		$res = Resource\Resource::find($req);
		$res->run($req);
	}

	public function provider()
	{
		$req1 = new Request(["REQUEST_METHOD" => "GET", "REQUEST_URI" => "/examples"]);
		$req2 = new Request(["REQUEST_METHOD" => "DELETE", "REQUEST_URI" => "/examples/10"]);

		return [
			[$req1, Examples::class],
			[$req2, Example::class]
		];
	}
}
