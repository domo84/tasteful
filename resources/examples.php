<?php

/*

Resources:
/examples (all)
/examples/1 (one)
/examples/1/items (subitems all)
/examples/1/items/1 (subitems one)

*/

namespace Sunnyvale\Resource;

use Sunnyvale\Resource\Many;

class Examples extends Many
{
    use DB;

    /**
     * GET /examples
     */
    public function get(Request $request): Response\JSON
    {
        $contents = $this->db("Example")->all();
        return new Response\JSON($contents);
    }

    /**
     * POST /examples '{ "name": "Name" }'
     */
    public function post(Request $request): Response\JSON
    {
        $id = $this->db("Example")->insert($request->body);
        $content = $this->db("Example")->one($id);
        return new Response\JSON($content);
    }
}
