<?php

namespace Sunnyvale\TEST\Resources;

use Tasteful\Request;
use Tasteful\Response;

use Tasteful\Interfaces\Resource;
use Tasteful\Interfaces\LinkedData;

trait DGPP
{
    public function delete(Request $request): Response
    {
        return new Response\NoContent();
    }

    public function get(Request $request): Response
    {
        return new Response\OK\JSON(array("some" => "thing"));
    }

    public function head(Request $request): Response
    {
        $response = $this->get($request);
        $response->body = null;
        return $response;
    }

    public function options(Request $request): Response
    {
        return new Response\Options;
    }

    public function post(Request $request): Response
    {
        return new Response\OK\JSON(array("some" => "thing"));
    }

    public function put(Request $request): Response
    {
        return new Response\OK\JSON(array("some" => "thing"));
    }
}

// @codingStandardsIgnoreLine
final class Examples implements Resource
{
    use DGPP;
}

// @codingStandardsIgnoreLine
final class Articles implements Resource
{
    use DGPP;
}

// @codingStandardsIgnoreLine
final class Users implements Resource
{
    use DGPP;

    public function get(Request $request): Response
    {
        throw new \Tasteful\Exceptions\NotFound("user not found");
    }

    public function post(Request $request): Response
    {
        throw new \Tasteful\Exceptions\MissingParameter("requires 'name'");
    }
}

// @codingStandardsIgnoreLine
final class Adventures implements Resource, LinkedData
{
    use DGPP;

    public function toLinkedData(Response\OK\JSON $response): Response\OK\LinkedData
    {
        return new Response\OK\LinkedData($response->body);
    }
}
