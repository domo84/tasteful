<?php

namespace Sunnyvale\REST;

use Sunnyvale\REST\Exceptions\Resource\Verb\NotImplemented as NotImplemented;

abstract class Resource
{
    public function get(Request $request)
    {
        throw new NotImplemented("GET");
    }

    public function post(Request $request)
    {
        throw new NotImplemented("POST");
    }

    public function put(Request $request)
    {
        throw new NotImplemented("PUT");
    }

    public function delete(Request $request)
    {
        throw new NotImplemented("DELETE");
    }

    public function options(Request $request)
    {
        return new Response\Options();
    }

    public function run()
    {
        $method = strtolower($this->request->method);

        if (method_exists($this, $method)) {
            return $this->$method($this->request);
        }

        throw new Sunnyvale\REST\Exceptions\Resource\Verb\NotSupported($method);
    }
}
