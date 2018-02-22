<?php

namespace Sunnyvale\TEST;

use PHPUnit\Framework\TestCase;
use Sunnyvale\REST\Server;
use Sunnyvale\REST\Response;
use Sunnyvale\REST\Request;
use Sunnyvale\REST\Resource;

use Sunnyvale\TEST\Resources\Users;

/**
 * @covers \Sunnyvale\REST\Server
 */
final class ServerTest extends TestCase
{
    public function testIndex()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "DELETE", "REQUEST_URI" => ""]);
        $response = $server->run();
        $this->assertInstanceOf(Response\JSON::class, $response);
    }

    public function testNotFound()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "DELETE", "REQUEST_URI" => "/examples"]);
        $server->run();
        $response = $server->run();
        $this->assertInstanceOf(Response\NotFound::class, $response);
    }

    /**
     * Testing registered but not implemented handler
     */
    public function testNotImplemented()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "POST", "REQUEST_URI" => "/examples"]);
        $server->resources = [
            "examples" => "\Sunnyvale\TEST\Resources\DoesNotExists"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\NotImplemented::class, $response);
    }

    public function testFound()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "GET", "REQUEST_URI" => "/examples"]);
        $server->resources = [
            "examples" => "\Sunnyvale\TEST\Resources\Examples"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\JSON::class, $response);
    }

    public function testFoundButNotImplemented()
    {
        $this->markTestSkipped("Maybe move to implementation tests");

        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "POST", "REQUEST_URI" => "/articles"]);
        $server->resources = [
            "articles" => "\Sunnyvale\TEST\Resources\Articles"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\NotImplemented::class, $response);
    }

    public function testNotSupported()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "WHIPE", "REQUEST_URI" => "/users"]);
        $server->resources = [
            "users" => "\Sunnyvale\TEST\Resources\Users"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\NotImplemented::class, $response);
    }

    public function testSpecificResourceNotFound()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "GET", "REQUEST_URI" => "/users/10"]);
        $server->resources = [
            "users" => "\Sunnyvale\TEST\Resources\Users"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\NotFound::class, $response);
    }

    public function testUnprocessableEntity()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/json", "REQUEST_METHOD" => "POST", "REQUEST_URI" => "/users"]);
        $server->resources = [
            "users" => "\Sunnyvale\TEST\Resources\Users"
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
        $this->assertInstanceOf(Response\JSON::class, $response);
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
            "adventures" => "\Sunnyvale\TEST\Resources\Adventures"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\LinkedData::class, $response);
    }

    public function testNotAcceptableLinkedData()
    {
        $server = new Server(["HTTP_ACCEPT" => "application/ld+json", "REQUEST_METHOD" => "GET", "REQUEST_URI" => "/articles"]);
        $server->resources = [
            "articles" => "\Sunnyvale\TEST\Resources\Articles"
        ];
        $response = $server->run();
        $this->assertInstanceOf(Response\NotAcceptable::class, $response);
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
            "examples" => "\Sunnyvale\TEST\Resources\Examples"
        ];
        $response = $server->run();
        $server->output();
    }
}
