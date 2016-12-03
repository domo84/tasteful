<?php

class Request
{
	const GET = "GET";
	const POST = "POST";
	const DELETE = "DELETE";
	const HEAD = "HEAD";

	public $method;
	public $resource;
	public $resourceId = null;

	public function __construct($server)
	{
		$this->method = $server["REQUEST_METHOD"];
		$uri = $server["REQUEST_URI"];
		$this->parseRequestUri($uri);
	}

	private function parseRequestUri($uri)
	{
		$parts = explode("/", $uri);

		for($i = 1; $i < count($parts); $i++)
		{
			switch($i)
			{
				case 1:
				{
					$this->resource = $parts[1];
					break;
				}
				case 2:
				{
					$this->resourceId = $parts[2];
					break;
				}
			}
		}
	}
}
