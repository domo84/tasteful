<?php

require_once "core/response.php";

use PHPUnit\Framework\TestCase;

final class Response_Test extends TestCase
{
    public function examples()
    {
        return [
            [["name" => "Magnus", "content" => "Secrets"]],
            ['{"name": "Magnus", "content": "Secrets"}']
        ];
    }

    /**
     * @dataProvider examples
     */
    public function testJSON($example)
    {
        $response = new Response\JSON($example);
        $this->assertTrue(is_string($response->body));
        $body = json_decode($response->body, true);
        $this->assertEquals($example, $body);
    }

    public function testHeaders()
    {
        $headers = [
            "Access-Control-Allow-Origin: *",
            "Access-Control-Allow-Methods: DELETE, GET, HEAD, OPTIONS, POST, PUT",
            "Access-Control-Allow-Headers: Content-Type"
        ];

        $response = new Response\JSON(array());
        $this->assertEquals($headers, array_slice($response->headers, 0, 3));

        $response = new Response\Conflict();
        $this->assertEquals($headers, array_slice($response->headers, 0, 3));

        $response = new Response\NotFound();
        $this->assertEquals($headers, array_slice($response->headers, 0, 3));

        $response = new Response\NoContent();
        $this->assertEquals($headers, array_slice($response->headers, 0, 3));
    }

    public function testConflict()
    {
        $response = new Response\Conflict();
        $this->assertEquals(409, $response->code);
        $this->assertTrue(isset($response->headers));
        $this->assertNull($response->body);
    }

    public function testCreated()
    {
        $_SERVER["HTTP_HOST"] = "newplace.com";
        $location = "/examples/10";
        $header = "Location: http://" . $_SERVER["HTTP_HOST"] . $location;
        $response = new Response\Created($location);
        $this->assertEquals(201, $response->code);
        $this->assertEquals($header, end($response->headers));
        $this->assertNull($response->body);
    }

    public function testNotFound()
    {
        $response = new Response\NotFound();
        $this->assertEquals(404, $response->code);
        $this->assertNull($response->body);
    }

    public function testNoContent()
    {
        $response = new Response\NoContent();
        $this->assertEquals(204, $response->code);
        $this->assertNull($response->body);
    }
}
