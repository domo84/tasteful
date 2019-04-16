<?php

namespace Sunnyexample\Resources;

use Tasteful\Request;
use Tasteful\Resource;
use Tasteful\Response;
use Tasteful\Exceptions;

use Sunnyexample\Database\ExampleItem as DB;

class ExampleItems implements \Tasteful\Interfaces\Resource
{
    public function __construct()
    {
        $this->db = new DB;
    }

    public function delete(Request $request): Response
    {
        if (!isset($request->subresourceId)) {
            throw new Exceptions\NotSupported("DELETE");
        }

        $this->db->delete($request->subresourceId);
        return new Response\NoContent();
    }

    public function get(Request $request): Response
    {
        $db = $this->db;
        if ($subresourceId = $request->subresourceId) {
            $content = $db->one($subresourceId);
        } else {
            $content = $db->allByParentId($request->resourceId);
        }

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

    public function put(Request $request): Response
    {
        $this->db->update($request->subresourceId, $request->body);
        $content = $this->db->one($request->subresourceId);
        return new Response\JSON($content);
    }

    public function post(Request $request): Response
    {
        $id = $this->db->insert($request->body);
        $content = $this->db->one($id);
        return new Response\JSON($content);
    }
}
