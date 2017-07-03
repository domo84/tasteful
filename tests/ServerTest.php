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
        $server = new Server(["REQUEST_METHOD" => "DELETE", "REQUEST_URI" => ""]);
        $result = $server->run();
        $this->assertInstanceOf(Response\JSON::class, $result);
    }

    public function testNotFound()
    {
        $server = new Server(["REQUEST_METHOD" => "DELETE", "REQUEST_URI" => "/examples"]);
        $server->run();
        $result = $server->run();
        $this->assertInstanceOf(Response\NotFound::class, $result);
    }

    /**
     * Testing registered but not implemented handler
     */
    public function testNotImplemented()
    {
        $server = new Server(["REQUEST_METHOD" => "POST", "REQUEST_URI" => "/examples"]);
        $server->resources = [
            "examples" => "\Sunnyvale\TEST\Resources\DoesNotExists"
        ];
        $result = $server->run();
        $this->assertInstanceOf(Response\NotImplemented::class, $result);
    }

    public function testFound()
    {
        $server = new Server(["REQUEST_METHOD" => "GET", "REQUEST_URI" => "/examples"]);
        $server->resources = [
            "examples" => "\Sunnyvale\TEST\Resources\Examples"
        ];
        $result = $server->run();
        $this->assertInstanceOf(Response\JSON::class, $result);
    }

    public function testFoundButNotImplemented()
    {
        $server = new Server(["REQUEST_METHOD" => "POST", "REQUEST_URI" => "/articles"]);
        $server->resources = [
            "articles" => "\Sunnyvale\TEST\Resources\Articles"
        ];
        $result = $server->run();
        $this->assertInstanceOf(Response\NotImplemented::class, $result);
    }

    public function testNotSupported()
    {
        $server = new Server(["REQUEST_METHOD" => "WHIPE", "REQUEST_URI" => "/users"]);
        $server->resources = [
            "users" => "\Sunnyvale\TEST\Resources\Users"
        ];
        $result = $server->run();
        $this->assertInstanceOf(Response\NotImplemented::class, $result);
    }

    public function testSpecificResourceNotFound()
    {
        $server = new Server(["REQUEST_METHOD" => "GET", "REQUEST_URI" => "/users/10"]);
        $server->resources = [
            "users" => "\Sunnyvale\TEST\Resources\Users"
        ];
        $result = $server->run();
        $this->assertInstanceOf(Response\NotFound::class, $result);
    }

    public function testUnprocessableEntity()
    {
        $server = new Server(["REQUEST_METHOD" => "POST", "REQUEST_URI" => "/users"]);
        $server->resources = [
            "users" => "\Sunnyvale\TEST\Resources\Users"
        ];
        $result = $server->run();
        $this->assertInstanceOf(Response\UnprocessableEntity::class, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testOutput()
    {
        $expected = '{"some":"thing"}' . "\n\r\n";
        $this->expectOutputString($expected);
        $server = new Server(["REQUEST_METHOD" => "GET", "REQUEST_URI" => "/examples"]);
        $server->resources = [
            "examples" => "\Sunnyvale\TEST\Resources\Examples"
        ];
        $result = $server->run();
        $server->output();
    }
}