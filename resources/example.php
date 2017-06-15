<?php

/*

Resources:
/examples (all)
/examples/1 (one)
/examples/1/items (subitems all)
/examples/1/items/1 (subitems one)

*/

class Examples extends Resource\Many
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

class Example extends Resource\One
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

class Example_Items extends Subresource\Many
{
	use DB;

	/**
	 * GET /examples/1/items
	 */
	public function get(Request $request): Response\JSON
	{
		$id = $request->resourceId;
		$content = $this->db("Example_Item")->allByParentId($id);
		return new Response\JSON($content);
	}

	/**
	 * POST /examples/1/items '{ "title": "Title" }'
	 */
	public function post(Request $request): Response\JSON
	{
		$id = $this->db("Example_Item")->insert($request->resourceId, $request->body);
		$content = $this->db("Example_Item")->one($id);
		return new Response\JSON($content);
	}
}

class Example_Item extends Subresource\One
{
	use DB;

	/**
	 * GET /examples/1/items/1
	 */
	public function get(Request $request): Response\JSON
	{
		$id = $request->resourceId;
		$subId = $request->subresourceId;
	}

	/**
	 * PUT /examples/1/items/1 '{ "title": "New title" }'
	 */
	public function put(Request $request): Response\JSON
	{
		$id = $request->resourceId;
		$subId = $request->subresourceId;
		$content = $request->body;
	}

	public function delete(Request $request): Response\NoContent
	{
		$this->db("Example_Item")->delete($request->subresourceId);
		return new Response\NoContent;
	}
}
