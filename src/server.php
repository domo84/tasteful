<?php

namespace Sunnyvale\REST;

require "exceptions.php";

class Server
{
    public $resources = array();
    public $standardResponse = array("name" => "Sunnyvale API", "version" => 1);

    public function run()
    {
        try {
            $request = new Request($_SERVER);

            if ($request->resource == null) {
                $response = new Response\JSON($this->standardResponse);
            } else {
                if (!isset($this->resources[$request->path])) {
                    throw new \Exception\Resource\NotFound($request->resource);
                }

                $class = $this->resources[$request->path];
                $resource = new $class();
                $resource->request = $request;
                $response = $resource->run();
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
        } catch (\Exception\Resource\NotFound $e) {
            http_response_code(404);
            error_log(sprintf("The resource '%s' you are looking for does not exist", $e->getMessage()));
        } catch (\Exception\Resource\Verb\NotSupported $e) {
            http_response_code(501);
            error_log(sprintf("The HTTP verb '%s' is not supported", $e->getMessage()));
        } catch (\Exception\Resource\Verb\NotImplemented $e) {
            http_response_code(501);
            error_log(sprintf("The HTTP verb '%s' is not yet implemeneted for this resource", $e->getMessage()));
        } catch (\Exception\Subresource\NotFound $e) {
            http_response_code(404);
            error_log(sprintf("The subresource '%s' you are looking for does not exists", $e->getMessage()));
        } catch (\Exception\Database\MissingKey $e) {
            http_response_code(422);
            error_log(sprintf("Client sent faulty data: %s", $e->getMessage()));
        }
    }
}
