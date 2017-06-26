<?php

namespace Sunnyexample\Resources;

use Sunnyvale\REST\Request;
use Sunnyvale\REST\Resource;
use Sunnyvale\REST\Response;

class Example_Items extends Resource
{
    public function __construct()
    {
        $this->db = new \Sunnyexample\Database\Example_Item();
    }

    public function delete(Request $request): Response\NoContent
    {
        $this->db->delete($request->subresourceId);
        return new Response\NoContent();
    }

    public function get(Request $request): Response\JSON
    {
        $db = $this->db;
        if ($subresourceId = $request->subresourceId) {
            $content = $db->one($subresourceId);
        } else {
            $content = $db->allByParentId($request->resourceId);
        }

        return new Response\JSON($content);
    }

    public function put(Request $request): Response\JSON
    {
        $this->db->update($request->subresourceId, $request->body);
        $content = $this->db->one($request->subresourceId);
        return new Response\JSON($content);
    }

    public function post(Request $request): Response\JSON
    {
        $id = $this->db->insert($request->body);
        $content = $this->db->one($id);
        return new Response\JSON($content);
    }
}
