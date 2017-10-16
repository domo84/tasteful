<?php

namespace Sunnyvale\TEST;

use PHPUnit\Framework\TestCase;

use Sunnyvale\REST\Resource;
use Sunnyvale\REST\Request;
use Sunnyvale\REST\Response;

use Sunnyvale\TEST\Resources\Examples;
use Sunnyvale\TEST\Resources\Articles;

/**
 * @covers \Sunnyvale\REST\Resource
 */
final class ResourceTest extends TestCase
{
    public function setUp()
    {
        $this->markTestSkipped("Maybe should move to implementation tests");
    }

    /**
     * @dataProvider okProvider
     */
    public function testRun($res, $instance)
    {
        $result = $res->run();
        $this->assertInstanceOf($instance, $result);
    }

    /**
     * @expectedException Sunnyvale\REST\Exceptions\NotImplemented
     * @dataProvider notImplementedProvider
     */
    public function testRunNonImplementedMethods($res)
    {
        $res->run();
    }

    /**
     * @expectedException Sunnyvale\REST\Exceptions\NotSupported
     */
    public function testNotSupported()
    {
        $req = new Request(["REQUEST_METHOD" => "COMPLETELY_WHIPE_EVERYTHING", "REQUEST_URI" => "/examples/10"]);
        $res = new Examples();
        $res->request = $req;
        $res->run();
    }

    public function notImplementedProvider()
    {
        $req1 = new Request(["REQUEST_METHOD" => "POST", "REQUEST_URI" => "/articles"]);
        $res1 = new Articles();
        $res1->request = $req1;

        $req2 = new Request(["REQUEST_METHOD" => "DELETE", "REQUEST_URI" => "/articles/10"]);
        $res2 = new Articles();
        $res2->request = $req2;

        $req3 = new Request(["REQUEST_METHOD" => "GET", "REQUEST_URI" => "/articles"]);
        $res3 = new Articles();
        $res3->request = $req3;

        $req4 = new Request(["REQUEST_METHOD" => "PUT", "REQUEST_URI" => "/articles/10"]);
        $res4 = new Articles();
        $res4->request = $req4;

        return [[$res1], [$res2], [$res3], [$res4]];
    }

    public function okProvider()
    {
        $req1 = new Request(["REQUEST_METHOD" => "POST", "REQUEST_URI" => "/examples"]);
        $res1 = new Examples();
        $res1->request = $req1;

        $req2 = new Request(["REQUEST_METHOD" => "DELETE", "REQUEST_URI" => "/examples/10"]);
        $res2 = new Examples();
        $res2->request = $req2;

        $req3 = new Request(["REQUEST_METHOD" => "GET", "REQUEST_URI" => "/examples"]);
        $res3 = new Examples();
        $res3->request = $req3;

        $req4 = new Request(["REQUEST_METHOD" => "PUT", "REQUEST_URI" => "/examples/10"]);
        $res4 = new Examples();
        $res4->request = $req4;

        $req5 = new Request(["REQUEST_METHOD" => "OPTIONS", "REQUEST_URI" => "/examples/10"]);
        $res5 = new Examples();
        $res5->request = $req5;

        return [
            [$res1, Response\JSON::class],
            [$res2, Response\NoContent::class],
            [$res3, Response\JSON::class],
            [$res4, Response\JSON::class],
            [$res5, Response\Options::class]
        ];
    }
}
