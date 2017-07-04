<?php

namespace Sunnyvale\REST;

class Server
{
    private $server = null;

    public $response = null;
    public $resources = array();
    public $name = "Sunnyvale API";
    public $version = 1;

    public function __construct($server)
    {
        $this->server = $server;
    }

    /**
     * @return Response
     */
    public function run()
    {
        try {
            $request = new Request($this->server);

            if ($request->resource == null) {
                $standard = array("name" => $this->name, "version" => $this->version);
                $this->response = new Response\JSON($standard);
                return $this->response;
            }
           
            if (!isset($this->resources[$request->path])) {
                $this->response = new Response\NotFound();
                return $this->response;
            }

            $class = $this->resources[$request->path];

            if (!class_exists($class)) {
                $this->response = new Response\NotImplemented();
                return $this->response;
            }

            $resource = new $class();
            $resource->request = $request;
            $this->response = $resource->run();
        } catch (Exceptions\NotFound $e) {
            $this->response = new Response\NotFound();
            // http_response_code(404);
            // error_log(sprintf("The resource '%s' you are looking for does not exist", $e->getMessage()));
        } catch (Exceptions\NotSupported $e) {
            $this->response = new Response\NotImplemented();
            // http_response_code(501);
            // error_log(sprintf("The HTTP verb '%s' is not supported", $e->getMessage()));
        } catch (Exceptions\NotImplemented $e) {
            $this->response = new Response\NotImplemented();
            // http_response_code(501);
            // error_log(sprintf("The HTTP verb '%s' is not implemeneted for this resource", $e->getMessage()));
        } catch (Exceptions\MissingParameter $e) {
            $this->response = new Response\UnprocessableEntity();
            // http_response_code(422);
            // error_log(sprintf("Client sent faulty data: %s", $e->getMessage()));
        }

        return $this->response;
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
