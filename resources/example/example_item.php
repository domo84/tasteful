<?php

namespace Sunnyvale\Resource\Example;

use Sunnyvale\Subresource\One;

class Item extends One
{
    use DB;

    public function delete(Request $request): Response\NoContent
    {
        $this->db("Example_Item")->delete($request->subresourceId);
        return new Response\NoContent();
    }

    /**
     * GET /examples/1/items/1
     */
    public function get(Request $request): Response\JSON
    {
        $content = $this->db("Example_Item")->one($request->subresourceId);
        return new Response\JSON($content);
    }

    /**
     * PUT /examples/1/items/1 '{ "title": "New title" }'
     */
    public function put(Request $request): Response\JSON
    {
        $this->db("Example_Item")->update($request->subresourceId, $request->body);
        $content = $this->db("Example_Item")->one($request->subresourceId);
        return new Response\JSON($content);
    }
}
