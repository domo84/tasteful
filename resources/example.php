<?php

namespace Sunnyvale\Resource;

use Sunnvale\Resource\One;

class Example extends One
{
    use DB;

    /**
     * DELETE /example/1
     */
    public function delete(Request $request): Response\NoContent
    {
        $this->db("Example")->delete($request->resourceId);
        return new Response\NoContent();
    }

    /**
     * GET /examples/1
     */
    public function get(Request $request): Response\JSON
    {
        $content = $this->db("Example")->one($request->resourceId);
        return new Response\JSON($content);
    }

    /**
     * PUT /examples/1 '{ "name": "New name" }'
     */
    public function put(Request $request): Response\JSON
    {
        $this->db("Example")->update($request->resourceId, $request->body);
        $content = $this->db("Example")->one($request->resourceId);
        return new Response\JSON($content);
    }
}
