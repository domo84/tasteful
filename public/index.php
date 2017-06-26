<?php

require_once "../psr4-autoloader.php";

$loader = new Sunnyvale\Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace("Sunnyvale\REST", "../src");
$loader->addNamespace("Sunnyvale\REST", "../resources");

# require_once __DIR__ . '/vendor/autoload.php';

# Core
# require_once "../core/exceptions.php";
# require_once "../core/request.php";
# require_once "../core/response.php";
# require_once "../core/resource.php";
# require_once "../core/subresource.php";
# require_once "../core/traits.php";

# Mods
# require_once "../database.php";
# require_once "../resources/example.php";

try {
    $request = new Request($_SERVER);

    if ($request->subresource) {
        $subresource = \Subresource\Subresource::find($request);
        $response = $subresource->run($request);
    } elseif ($request->resource) {
        $resource = \Resource\Resource::find($request);
        $response = $resource->run($request);
    } else {
        $content = array(
            "name" => "Sunnyvale API",
            "version" => 1
        );
        $response = new Response\JSON($content);
    }

    foreach ($response->headers as $header) {
        header($header);
    }

    if ($response->code) {
        http_response_code($response->code);
    }

    if ($response->body != null) {
        echo $response->body;
        echo "\n";
    }
} catch (Exception\Resource\NotFound $e) {
    http_response_code(404);
    error_log(sprintf("The resource '%s' you are looking for does not exist", $e->getMessage()));
} catch (Exception\Resource\Verb\NotSupported $e) {
    http_response_code(501);
    error_log(sprintf("The HTTP verb '%s' is not supported", $e->getMessage()));
} catch (Exception\Resource\Verb\NotImplemented $e) {
    http_response_code(501);
    error_log(sprintf("The HTTP verb '%s' is not yet implemeneted for this resource", $e->getMessage()));
} catch (Exception\Subresource\NotFound $e) {
    http_response_code(404);
    error_log(sprintf("The subresource '%s' you are looking for does not exists", $e->getMessage()));
} catch (Exception\Database\MissingKey $e) {
    http_response_code(422);
    error_log(sprintf("Client sent faulty data: %s", $e->getMessage()));
}
