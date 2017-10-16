<?php

namespace Sunnyexample\Resources;

use Sunnyvale\REST\Request;
use Sunnyvale\REST\Resource;
use Sunnyvale\REST\Response;
use Sunnyvale\REST\Exceptions;

use Sunnyexample\Database\ExampleItem as DB;

class ExampleItems implements \Sunnyvale\REST\Interfaces\Resource
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
