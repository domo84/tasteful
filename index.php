<?php

require_once __DIR__ . '/vendor/autoload.php';

# Core
require_once "exceptions.php";
require_once "request.php";
require_once "response.php";
require_once "resource.php";
require_once "subresource.php";
require_once "traits.php";

# Mods
# require_once "database.php";
# require_once "resources/example.php";

define("CONTENT_PATH", __DIR__ . "/../content");

try
{
	$request = new Request($_SERVER);

	if(is_null($request->subresource))
	{
		$resource = Resource::find($request);
		$response = $resource->run($request);
	}
	else
	{
		$subresource = Subresource::find($request);
		$response = $subresource->run($request);
	}

	foreach($response->headers as $header)
	{
		header($header);
	}

	if($response->code)
	{
		http_response_code($response->code);
	}

	if($response->body != null)
	{
		echo $response->body;
		echo "\n";
	}
}
catch(Exception\Resource\NotFound $e)
{
	http_response_code(404);
	error_log(sprintf("The resource '%s' you are looking for does not exist", $e->getMessage()));
}
catch(Exception\Resource\Verb\NotSupported $e)
{
	http_response_code(501);
	error_log(sprintf("The HTTP verb '%s' is not supported", $e->getMessage()));
}
catch(Exception\Resource\Verb\NotImplemented $e)
{
	http_response_code(501);
	error_log(sprintf("The HTTP verb '%s' is not yet implemeneted for this resource", $e->getMessage()));
}
catch(Exception\Subresource\NotFound $e)
{
	http_response_code(404);
	error_log(sprintf("The subresource '%s' you are looking for does not exists", $e->getMessage()));
}
