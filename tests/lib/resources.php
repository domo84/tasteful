<?php

namespace Sunnyvale\TEST\Resources;

# use Sunnyvale\REST\Resource;
use Sunnyvale\REST\Request;
use Sunnyvale\REST\Response;

use Sunnyvale\REST\Interfaces\Resource;

trait DGPP
{
    public function delete(Request $request): Response
    {
        return new Response\NoContent();
    }

    public function get(Request $request): Response
    {
        return new Response\JSON(array("some" => "thing"));
    }

    public function post(Request $request): Response
    {
        return new Response\JSON(array("some" => "thing"));
    }

    public function put(Request $request): Response
    {
        return new Response\JSON(array("some" => "thing"));
    }
}

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
        throw new \Sunnyvale\REST\Exceptions\NotFound("user not found");
    }

    public function post(Request $request): Response
    {
        throw new \Sunnyvale\REST\Exceptions\MissingParameter("requires 'name'");
    }
}
