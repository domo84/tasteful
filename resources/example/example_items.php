<?php

namespace Sunnyvale\Resource\Example;

use Sunnyvale\Resource\Many;

class Items extends Many
{
    use DB;

    // GET /examples/1/items
    public function get(Request $request): Response\JSON
    {
        $id = $request->resourceId;
        $content = $this->db("Example_Item")->allByParentId($id);
        return new Response\JSON($content);
    }

    // POST /examples/1/items '{ "title": "Title" }'
    public function post(Request $request): Response\JSON
    {
        $id = $this->db("Example_Item")->insert($request->body);
        $content = $this->db("Example_Item")->one($id);
        return new Response\JSON($content);
    }
}
