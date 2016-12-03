<?php

abstract class Resource
{
	public function get()
	{
		throw new Exception\Resource\Verb\NotImplemented();
	}

	public function post()
	{
		throw new Exception\Resource\Verb\NotImplemented();
	}

	public function put()
	{
		throw new Exception\Resource\Verb\NotImplemented();
	}

	public function delete()
	{
		throw new Exception\Resource\Verb\NotImplemented();
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
			throw new Exception\Resource\NotFound();
		}
	}

	public function run($request)
	{
		$method = strtolower($request->method);

		if(method_exists($this, $method))
		{
			if($request->resourceId == null)
			{
				return $this->$method();
			}
			else
			{
				return $this->$method($request->resourceId);
			}
		}
		else
		{
			throw new Exception\Resource\Verb\NotSupported();
		}
	}
}

