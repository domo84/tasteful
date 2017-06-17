<?php

namespace Subresource;

use \Exception\Resource\Verb\NotImplemented as NotImplemented;

abstract class One extends Subresource
{
    public function get(\Request $request): \Response\JSON
    {
        throw new NotImplemented("GET");
    }

    public function post(\Request $request): \Response\JSON
    {
        throw new NotImplemented("POST");
    }

    public function put(\Request $request): \Response\JSON
    {
        throw new NotImplemented("PUT");
    }

    public function delete(\Request $request): \Response\NoContent
    {
        throw new NotImplemented("DELETE");
    }

    public function options(\Request $request): \Response\Options
    {
        return new \Response\Options();
    }
}

abstract class Many extends Subresource
{
    public function get(\Request $request): \Response\JSON
    {
        throw new NotImplemented("GET");
    }

    public function post(\Request $request): \Response\JSON
    {
        throw new NotImplemented("POST");
    }

    public function put(\Request $request): \Response\JSON
    {
        throw new NotImplemented("PUT");
    }

    public function delete(\Request $request): \Response\NoContent
    {
        throw new NotImplemented("DELETE");
    }

    public function options(\Request $request): \Response\Options
    {
        return new \Response\Options();
    }
}

abstract class Subresource
{
    public static function find(\Request $request)
    {
        if ($request->subresourceId == null) {
            $class = sprintf("%s_%s", rtrim($request->resource, "s"), $request->subresource);
        } else {
            $class = sprintf("%s_%s", rtrim($request->resource, "s"), rtrim($request->subresource, "s"));
        }

        if (class_exists($class)) {
            $obj = new $class;
            $obj->request = $request;
            return $obj;
        }

        throw new \Exception\Resource\NotFound($class);
    }

    public function run()
    {
        $method = strtolower($this->request->method);

        if (method_exists($this, $method)) {
            return $this->$method($this->request);
        }

        throw new \Exception\Resource\Verb\NotSupported($method);
    }
}
