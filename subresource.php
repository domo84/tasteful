<?php

abstract class Subresource extends Resource
{
	public static function find($request)
	{
		if($request->subresourceId == null)
		{
			$class = sprintf("%s_%s", rtrim($this->resource, "s"), $request->subresource);
		}
		else
		{
			$class = sprintf("%s_%s", rtrim($this->resource, "s"), rtrim($request->subresource, "s"));
		}

		if(class_exists($class))
		{
			$obj = new $class;

			if($request->subresourceId != null)
			{
				$obj->subresourceId = $request->subresourceId;
			}

			$obj->resourceId = $request->resourceId;

			return $obj;
		}
		else
		{
			throw new Exception\Subresource\NotFound($class);
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
			else if($request->subresourceId == null)
			{
				return $this->$method($this->resourceId, $body);
			}
			else
			{
				return $this->$method($request->resourceId, $request->subresourceId, $body);
			}
		}
		else
		{
			throw new Exception\Subresource\Verb\NotSupported($method);
		}
	}
}
