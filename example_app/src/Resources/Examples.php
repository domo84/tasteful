<?php

namespace Sunnyexample\Resources;

use Sunnyvale\REST\Request;
use Sunnyvale\REST\Resource;
use Sunnyvale\REST\Response;
use Sunnyvale\REST\Exceptions;

class Examples implements \Sunnyvale\REST\Interfaces\Resource
{
    public function __construct()
    {
        $this->db = new \Sunnyexample\Database\Example();
    }

    public function delete(Request $request): Response
    {
        if (!isset($request->resourceId)) {
            throw new Exceptions\NotSupported("DELETE");
        }

        $this->db->delete($request->resourceId);
        return new Response\NoContent();
    }

    public function get(Request $request): Response
    {
        $db = $this->db;
        $content = ($id = $request->resourceId) ? $db->one($id) : $db->all();
        return new Response\JSON($content);
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
        $id = $this->db->insert($request->body);
        $content = $this->db->one($id);
        return new Response\JSON($content);
    }

    public function put(Request $request): Response
    {
        $this->db->update($request->resourceId, $request->body);
        $content = $this->db->one($request->resourceId);
        return new Response\JSON($content);
    }
}
