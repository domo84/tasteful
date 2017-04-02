<?php

/*

Resources:
/example (all)
/example/1 (one)
/example/1/items (subitems all)
/example/1/items/1 (subitems one)

*/

class Examples extends Resource
{
	/**
	 * GET /example
	 */
	public function get(Request $request)
	{
	}

	/**
	 * POST /example '{ "name": "Name" }'
	 */
	public function post(Request $request)
	{
		$content = $request->body;
	}
}

class Example extends Resource
{
	/**
	 * GET /example/1
	 */
	public function get(Request $request)
	{
		$id = $request->resourceId;
	}

	/**
	 * PUT /example/1 '{ "name": "New name" }'
	 */
	public function put(Request $request)
	{
		$id = $request->resourceId;
		$content = $request->body;
	}
}

class Example_Items extends Subresource
{
	/**
	 * GET /example/1/items
	 */
	public function get(Request $request)
	{
		$id = $request->resourceId;
	}

	/**
	 * POST /example/1/items '{ "title": "Title" }'
	 */
	public function post(Request $request)
	{
		$id = $request->resourceId;
		$content = $request->body;
	}
}

class Example_Item extends Subresource
{
	/**
	 * GET /example/1/items/1
	 */
	public function get(Request $request)
	{
		$id = $request->resourceId;
		$subId = $request->subresourceId;
	}

	/**
	 * PUT /example/1/items/1 '{ "title": "New title" }'
	 */
	public function put(Request $request)
	{
		$id = $request->resourceId;
		$subId = $request->subresourceId;
		$content = $request->body;
	}
}
