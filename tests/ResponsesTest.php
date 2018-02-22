<?php

namespace Sunnyvale\TEST;

use PHPUnit\Framework\TestCase;
use Sunnyvale\REST\Response;

class ResponsesTest extends TestCase
{
    /**
     * @covers \Sunnyvale\REST\Response\NotFound
     */
    public function testNotFound()
    {
        $response = new Response\NotFound();
        $this->assertEquals(404, $response->code);
        $this->assertNull($response->body);
    }

    /**
     * @covers \Sunnyvale\REST\Response\Conflict
     */
    public function testConflict()
    {
        $response = new Response\Conflict();
        $this->assertEquals(409, $response->code);
        $this->assertNull($response->body);
    }

    /**
     * @covers \Sunnyvale\REST\Response\NoContent
     */
    public function testNoContent()
    {
        $response = new Response\NoContent();
        $this->assertEquals(204, $response->code);
        $this->assertNull($response->body);
    }

    /**
     * @covers \Sunnyvale\REST\Response\Created
     */
    public function testCreated()
    {
        $_SERVER["HTTP_HOST"] = "localhost";
        $response = new Response\Created("/examples/100");
        $this->assertEquals(201, $response->code);
        $this->assertEquals("Location: http://localhost/examples/100", end($response->headers));
        $this->assertNull($response->body);
    }

    /**
     * @covers \Sunnyvale\REST\Response\OK\JSON
     */
    public function testJSON()
    {
        $example = array("name" => "A Sunny example!");
        $response = new Response\OK\JSON($example);
        $this->assertEquals(200, $response->code);
        $this->assertTrue(is_string($response->body));
        $body = json_decode($response->body, true);
        $this->assertEquals($example, $body);

        $res2 = new Response\OK\JSON('[{"name": "A Sunny example!"}]');
        $this->assertEquals(200, $res2->code);

        $res3 = new Response\OK\JSON('{"name": "A Sunny example!"}');
        $this->assertEquals(200, $res3->code);

        $res4 = new Response\OK\JSON("Well, I dno");
        $this->assertEquals(200, $res4->code);
    }

    /**
     * @covers \Sunnyvale\REST\Response\NotImplemented
     */
    public function testNotImplemented()
    {
        $response = new Response\NotImplemented();
        $this->assertEquals(501, $response->code);
        $this->assertNull($response->body);
    }

    /**
     * @covers \Sunnyvale\REST\Response\UnprocessableEntity
     */
    public function testUnprocessableEntity()
    {
        $response = new Response\UnprocessableEntity();
        $this->assertEquals(422, $response->code);
        $this->assertNull($response->body);
    }

    /**
     * @covers \Sunnyvale\REST\Response\Options
     */
    public function testOptions()
    {
        $response = new Response\Options();
        $this->assertEquals(200, $response->code);
        $this->assertNull($response->body);
    }
}
