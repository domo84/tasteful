<?php

namespace Resource;

use \Exception\Resource\Verb\NotImplemented as NotImplemented;

abstract class One extends Resource
{
    public function get(\Request $request)
    {
        throw new NotImplemented("GET");
    }

    public function post(\Request $request)
    {
        throw new NotImplemented("POST");
    }

    public function put(\Request $request)
    {
        throw new NotImplemented("PUT");
    }

    public function delete(\Request $request)
    {
        throw new NotImplemented("DELETE");
    }

    public function options(\Request $request)
    {
        return new \Response\Options();
    }
}

abstract class Many extends Resource
{
    public function get(\Request $request)
    {
        throw new NotImplemented("GET");
    }

    public function post(\Request $request)
    {
        throw new NotImplemented("POST");
    }

    public function put(\Request $request)
    {
        throw new NotImplemented("PUT");
    }

    public function delete(\Request $request)
    {
        throw new NotImplemented("DELETE");
    }

    public function options(\Request $request)
    {
        return new \Response\Options();
    }
}

abstract class Resource
{
    public static function find(\Request $request)
    {
        if ($request->resourceId == null) {
            $class = ucfirst($request->resource);
        } else {
            $class = rtrim($request->resource, "s");
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
