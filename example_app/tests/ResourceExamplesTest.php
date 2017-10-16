<?php

namespace Sunnyexample\TEST;

use PHPUnit\Framework\TestCase;

final class ResourceExamplesTest extends TestCase
{
    use provider;

    private $rest_client = null;

    public function setUp()
    {
        $this->rest_client = new Rest_Client();
    }

    public function testGetIsEmpty()
    {
        list($result, $code) = $this->rest_client->get("examples");
        $this->assertEquals(0, count($result));
        $this->assertEquals(200, $code);
    }

    /**
     * @dataProvider examples
     */
    public function testPost($example)
    {
        list($result, $code) = $this->rest_client->post("examples", $example);
        $this->assertEquals($example, $result);
        $this->assertEquals(200, $code);
    }

    /**
     * @dataProvider examples
     */
    public function testPostToNonExistentResource($example)
    {
        list($result, $code) = $this->rest_client->post("doesnotexists", $example);
        $this->assertNull($result);
        $this->assertEquals(404, $code);
    }

    /**
     * @dataProvider faultyExamples
     */
    public function testPostFaultyData($example)
    {
        list($result, $code) = $this->rest_client->post("examples", $example);
        $this->assertNull($result);
        $this->assertEquals(422, $code);
    }

    public function testGetIsNotEmpty()
    {
        list($result, $code) = $this->rest_client->get("examples");
        $this->assertEquals(2, count($result));
        $this->assertEquals(200, $code);
    }

    /**
     * @dataProvider moreExamples
     */
    public function testPut($example)
    {
        $id = $example["id"];
        list($result, $code) = $this->rest_client->put("examples/$id", $example);
        $this->assertEquals($example, $result);
        $this->assertEquals(200, $code);
    }

    /**
     * @dataProvider examples
     */
    public function testDelete($example)
    {
        $id = $example["id"];
        list($result, $code) = $this->rest_client->delete("examples/$id");
        $this->assertNull($result);
        $this->assertEquals(204, $code);
    }

    public function testDeleteResource()
    {
        list($result, $code) = $this->rest_client->delete("examples");
        $this->assertNull($result);
        $this->assertEquals(501, $code);
    }

    public function testDeleteNonExistentResource()
    {
        list($result, $code) = $this->rest_client->delete("doesnotexists");
        $this->assertNull($result);
        $this->assertEquals(404, $code);
    }

    public function testGet()
    {
        list($result, $code) = $this->rest_client->get("examples");
        $this->assertEquals(200, $code);
        $this->assertEquals(0, count($result));
    }

    public function tearDown()
    {
        $path = __DIR__ . "/../storage/services.db";
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
