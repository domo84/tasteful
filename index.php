<?php

# require __DIR__ . '/vendor/autoload.php';
require_once "exceptions.php";
require_once "request.php";
require_once "response.php";
require_once "resource.php";
require_once "resources/log.php";
require_once "resources/current.php";
require_once "resources/tracklog.php";

define("CONTENT_PATH", __DIR__ . "/../content");

try
{
	$request = new Request($_SERVER);
	$resource = Resource::find($request);
	$response = $resource->run($request);

	$response->headers[] = header("Access-Control-Allow-Origin: *");

	foreach($response->headers as $header)
	{
		header($header);
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
	error_log("The resource you are looking for does not exist");
}
catch(Exception\Resource\Verb\NotSupported $e)
{
	http_response_code(501);
	error_log("The HTTP verb is not supported");
}
catch(Exception\Resource\Verb\NotImplemented $e)
{
	http_response_code(501);
	error_log("The HTTP verb is not yet implemeneted for this resource");
}
