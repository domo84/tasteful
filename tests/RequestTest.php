<?php

namespace Sunnyvale\TEST;

use PHPUnit\Framework\TestCase;
use Tasteful\Request;

/**
 * @covers \Tasteful\Request
 */
final class RequestTest extends TestCase
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
        $this->assertEquals($expected["params"], $request->params);
        $this->assertEquals($expected["token"], $request->token);
    }

    /**
     * $server as in $_SERVER mock
     */
    public function provider()
    {
        $server1 = [
            "HTTP_ACCEPT" => "text/html",
            "REQUEST_METHOD" => "GET",
            "REQUEST_URI" => "/examples"
        ];

        $expected1 = [
            "method" => "GET",
            "path" => "examples",
            "resource" => "examples",
            "resourceId" => null,
            "body" => null,
            "params" => array(),
            "token" => null
        ];

        $server2 = [
            "HTTP_ACCEPT" => "text/html",
            "REQUEST_METHOD" => "DELETE",
            "REQUEST_URI" => "/examples/10"
        ];

        $expected2 = [
            "method" => "DELETE",
            "path" => "examples",
            "resource" => "examples",
            "resourceId" => 10,
            "body" => null,
            "params" => array(),
            "token" => null
        ];

        $server3 = [
            "HTTP_ACCEPT" => "text/html",
            "REQUEST_METHOD" => "POST",
            "REQUEST_URI" => "/examples/10/items",
            "_BODY" => json_encode(["name" => "Name#1", "email" => "mail@example.com"])
        ];

        $expected3 = [
            "method" => "POST",
            "path" => "examples/items",
            "resource" => "examples",
            "resourceId" => 10,
            "subresource" => "items",
            "body" => ["name" => "Name#1", "email" => "mail@example.com"],
            "params" => array(),
            "token" => null
        ];

        $server4 = [
            "HTTP_ACCEPT" => "text/html",
            "REQUEST_METHOD" => "PUT",
            "REQUEST_URI" => "/examples/10/items/4"
        ];

        $expected4 = [
            "method" => "PUT",
            "path" => "examples/items",
            "resource" => "examples",
            "resourceId" => 10,
            "subresource" => "items",
            "subresourceId" => 4,
            "body" => null,
            "params" => array(),
            "token" => null
        ];

        $server5 = [
            "HTTP_ACCEPT" => "text/html",
            "REQUEST_METHOD" => "OPTIONS",
            "REQUEST_URI" => "/examples"
        ];

        $expected5 = [
            "method" => "OPTIONS",
            "path" => "examples",
            "resource" => "examples",
            "resourceId" => null,
            "body" => null,
            "params" => array(),
            "token" => null
        ];

        $server6 = [
            "HTTP_ACCEPT" => "text/html",
            "REQUEST_METHOD" => "GET",
            "REQUEST_URI" => "/examples",
            "_GET" => ["q" => "tomatoes"]
        ];

        $expected6 = [
            "method" => "GET",
            "path" => "examples",
            "resource" => "examples",
            "resourceId" => null,
            "body" => null,
            "params" => ["q" => "tomatoes"],
            "token" => null
        ];

        $server7 = [
            "HTTP_ACCEPT" => "text/html",
            "REQUEST_METHOD" => "GET",
            "REQUEST_URI" => "/examples",
            "HTTP_AUTHORIZATION" => "token 123"
        ];

        $expected7 = [
            "method" => "GET",
            "path" => "examples",
            "resource" => "examples",
            "resourceId" => null,
            "body" => null,
            "params" => array(),
            "token" => "123"
        ];

        return [
            [$server1, $expected1],
            [$server2, $expected2],
            [$server3, $expected3],
            [$server4, $expected4],
            [$server5, $expected5],
            [$server6, $expected6],
            [$server7, $expected7]
        ];
    }
}
