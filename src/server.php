<?php

namespace Sunnyvale\REST;

class Server
{
    private $server = null;
    private $response = null;

    public $resources = array();
    public $standardResponse = array("name" => "Sunnyvale API", "version" => 1);

    public function __construct($server)
    {
        $this->server = $server;
    }

    public function run()
    {
        $response = null;
        $is_success = false;

        try {
            $request = new Request($this->server);

            if ($request->resource == null) {
                $response = new Response\JSON($this->standardResponse);
            } else {
                if (!isset($this->resources[$request->path])) {
                    throw new \DomainException($request->resource);
                }

                $class = $this->resources[$request->path];

                if (!class_exists($class)) {
                    throw new Exceptions\Resource\NotImplemented($class);
                }

                $resource = new $class();
                $resource->request = $request;
                $response = $resource->run();
            }

            $is_success = true;
        } catch (\DomainException $e) {
            http_response_code(404);
            error_log(sprintf("The resource '%s' you are looking for does not exist", $e->getMessage()));
        } catch (Exceptions\Resource\Verb\NotSupported $e) {
            http_response_code(501);
            error_log(sprintf("The HTTP verb '%s' is not supported", $e->getMessage()));
        } catch (Exceptions\Resource\Verb\NotImplemented $e) {
            http_response_code(501);
            error_log(sprintf("The HTTP verb '%s' is not yet implemeneted for this resource", $e->getMessage()));
        } catch (Exceptions\Subresource\NotFound $e) {
            http_response_code(404);
            error_log(sprintf("The subresource '%s' you are looking for does not exists", $e->getMessage()));
        } catch (\InvalidArgumentException $e) {
            http_response_code(422);
            error_log(sprintf("Client sent faulty data: %s", $e->getMessage()));
        }

        $this->response = $response;

        return $is_success;
    }

    public function output()
    {
        $response = $this->response;

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
    }
}
