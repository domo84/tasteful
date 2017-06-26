<?php

namespace Sunnyvale\TEST;

use PHPUnit\Framework\TestCase;
use Sunnyvale\REST\Server;
use Sunnyvale\REST\Response;
use Sunnyvale\REST\Resource;
use Sunnyvale\REST\Request;
use Sunnyvale\REST\Exceptions\Resource\NotFound;
use Sunnyvale\REST\Exceptions\Resource\NotImplemented;

final class Users extends Resource
{
    public function get(Request $request): Response\JSON
    {
        return new Response\JSON(array(array("name" => "Sungam")));
    }
}

// @codingStandardsIgnoreLine
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
        $this->expectException(NotFound::class);
        $server = new Server(["REQUEST_METHOD" => "DELETE", "REQUEST_URI" => "/examples"]);
        $server->run();
    }

    public function testNotImplemented()
    {
        $this->expectException(NotImplemented::class);
        $server = new Server(["REQUEST_METHOD" => "DELETE", "REQUEST_URI" => "/examples"]);
        $server->resources = [
            "examples" => "\Sunnyexample\Resources\Examples"
        ];
        $server->run();
    }

    public function testFound()
    {
        $server = new Server(["REQUEST_METHOD" => "GET", "REQUEST_URI" => "/users"]);
        $server->resources = [
            "users" => "\Sunnyvale\TEST\Users"
        ];
        $result = $server->run();
        $this->assertTrue(is_object($result));
    }

    public function testFoundButNotImplemented()
    {
        $this->expectException(NotImplemented::class);
        $server = new Server(["REQUEST_METHOD" => "POST", "REQUEST_URI" => "/users"]);
        $server->resources = [
            "users" => "Users"
        ];
        $server->run();
    }
}
