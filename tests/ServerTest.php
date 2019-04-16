<?php

namespace Tasteful\TEST;

use PHPUnit\Framework\TestCase;
use Tasteful\Server;
use Tasteful\Response;
use Tasteful\Request;
use Tasteful\Resource;

use Tasteful\TEST\Resources\Users;

/**
 * @covers \Tasteful\Server
 */
final class ServerTest extends TestCase
{
    public function testIndex()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "DELETE", "REQUEST_URI" => ""]);
        $response = $server->run();
        $this->assertInstanceOf(Response\OK\JSON::class, $response);
    }

    public function testNotFound()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "DELETE", "REQUEST_URI" => "/examples"]);
        $server->run();
        $response = $server->run();
        $this->assertInstanceOf(Response\NotFound::class, $response);
    }

	public function testAuthorizationRequired()
	{
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "POST", "REQUEST_URI" => "/examples"]);
		$this->assertFalse($server->authorizationRequired());

		$server->authorization = true;
		$this->assertTrue($server->authorizationRequired());
	}

	public function testAuthorizationWhitelist()
	{
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "GET", "REQUEST_URI" => "/examples"]);
		$server->authorizationWhitelist = [];
		$server->authorization = true;
		$this->assertTrue($server->authorizationRequired());

		$server->authorizationWhitelist = ["GET"];
		$this->assertFalse($server->authorizationRequired());
	}

    /**
     * Testing registered but not implemented handler
     */
    public function testNotImplemented()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "POST", "REQUEST_URI" => "/examples"]);
        $server->resources = [
            "examples" => "\Tasteful\TEST\Resources\DoesNotExists"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\NotImplemented::class, $response);
    }

    public function testFound()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "GET", "REQUEST_URI" => "/examples"]);
        $server->resources = [
            "examples" => "\Tasteful\TEST\Resources\Examples"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\OK\JSON::class, $response);
    }

    public function testFoundButNotImplemented()
    {
        $this->markTestSkipped("Maybe move to implementation tests");

        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "POST", "REQUEST_URI" => "/articles"]);
        $server->resources = [
            "articles" => "\Tasteful\TEST\Resources\Articles"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\NotImplemented::class, $response);
    }

    public function testNotSupported()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "WHIPE", "REQUEST_URI" => "/users"]);
        $server->resources = [
            "users" => "\Tasteful\TEST\Resources\Users"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\NotImplemented::class, $response);
    }

    public function testSpecificResourceNotFound()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "GET", "REQUEST_URI" => "/users/10"]);
        $server->resources = [
            "users" => "\Tasteful\TEST\Resources\Users"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\NotFound::class, $response);
    }

    public function testUnprocessableEntity()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "POST", "REQUEST_URI" => "/users"]);
        $server->resources = [
            "users" => "\Tasteful\TEST\Resources\Users"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\UnprocessableEntity::class, $response);
    }

    public function testAuthorizationSuccess()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "GET", "REQUEST_URI" => "", "HTTP_AUTHORIZATION" => "token 123"]);
        $server->authorization = true;
        $this->assertEquals("123", $server->request->token);
        $response = $server->run();
        $this->assertInstanceOf(Response\OK\JSON::class, $response);
    }

    public function testAuthorizationFail()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "POST", "REQUEST_URI" => ""]);
        $server->authorization = true;
        $this->assertNull($server->request->token);
        $response = $server->run();
        $this->assertInstanceOf(Response\Unauthorized::class, $response);
    }

    public function testAcceptLinkedData()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/ld+json", "REQUEST_METHOD" => "GET", "REQUEST_URI" => "/adventures/10"]);
        $server->resources = [
            "adventures" => "\Tasteful\TEST\Resources\Adventures"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\OK\LinkedData::class, $response);
    }

    public function testNotImplementedLinkedData()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/ld+json", "REQUEST_METHOD" => "GET", "REQUEST_URI" => "/articles"]);
        $server->resources = [
            "articles" => "\Tasteful\TEST\Resources\Articles"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\OK\JSON::class, $response);
    }

    /**
     * @runInSeparateProcess
     */
    public function _testOutput()
    {
        $expected = '{"some":"thing"}' . "\n\r\n";
        $this->expectOutputString($expected);
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "GET", "REQUEST_URI" => "/examples"]);
        $server->resources = [
            "examples" => "\Tasteful\TEST\Resources\Examples"
        ];
        $response = $server->run();
        $server->output();
    }
}
