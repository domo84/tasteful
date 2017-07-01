<?php

namespace Sunnyvale\TEST\Resources;

use Sunnyvale\REST\Resource;
use Sunnyvale\REST\Request;
use Sunnyvale\REST\Response;

final class Examples extends Resource
{
    public function delete(Request $request): Response\NoContent
    {
        return new Response\NoContent();
    }

    public function get(Request $request): Response\JSON
    {
        return new Response\JSON(array("some" => "thing"));
    }

    public function post(Request $request): Response\JSON
    {
        return new Response\JSON(array("some" => "thing"));
    }

    public function put(Request $request): Response\JSON
    {
        return new Response\JSON(array("some" => "thing"));
    }
}

// @codingStandardsIgnoreLine
final class Articles extends Resource
{
}

// @codingStandardsIgnoreLine
final class Users extends Resource
{
    public function get(Request $request): Response\JSON
    {
        throw new \Sunnyvale\REST\Exceptions\NotFound("user not found");
    }

    public function post(Request $request): Response\JSON
    {
        throw new \Sunnyvale\REST\Exceptions\MissingParameter("requires 'name'");
    }
}
