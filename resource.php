<?php

abstract class Resource
{
	public function get()
	{
		throw new Exception\Resource\Verb\NotImplemented("GET");
	}

	public function post()
	{
		throw new Exception\Resource\Verb\NotImplemented("POST");
	}

	public function put()
	{
		throw new Exception\Resource\Verb\NotImplemented("PUT");
	}

	public function delete()
	{
		throw new Exception\Resource\Verb\NotImplemented("DELETE");
	}

	public function options()
	{
		return new \Response\Options();
	}

	public static function find($request)
	{
		if($request->resourceId == null)
		{
			$class = ucfirst($request->resource);
		}
		else
		{
			$class = rtrim($request->resource, "s");
		}

		if(class_exists($class))
		{
			$obj = new $class;

			if($request->resourceId != null)
			{
				$obj->resourceId = $request->resourceId;
			}

			return new $class;
		}
		else
		{
			throw new Exception\Resource\NotFound($class);
		}
	}

	public function run($request)
	{
		$method = strtolower($request->method);
		$body = $request->body;

		if(method_exists($this, $method))
		{
			if($request->resourceId == null)
			{
				return $this->$method($body);
			}
			else
			{
				return $this->$method($request->resourceId, $body);
			}
		}
		else
		{
			throw new Exception\Resource\Verb\NotSupported($method);
		}
	}
}
