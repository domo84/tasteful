<?php

namespace Sunnyvale\REST;

class Server
{
    private $server = null;

    public $response = null;
    public $resources = array();
    public $name = "Sunnyvale API";
    public $version = 1;
    public $authorization = false;
    public $authorizationWhitelist = ["GET", "HEAD", "OPTIONS"];
    public $request;

    public function __construct($server)
    {
        $this->server = $server;
        $this->request = new Request($server);
    }

    public function authorizationRequired()
    {
        if ($this->authorization) {
            if (in_array($this->request->method, $this->authorizationWhitelist)) {
                // Request method is in whitelist. No Authorization required
				return false;
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Response
     */
    public function run()
    {
        $request = $this->request;

        # Authorization required or not
        if ($this->authorization && !in_array($request->method, $this->authorizationWhitelist)) {
            if (!$request->token) {
                $this->response = new Response\Unauthorized();
                return $this->response;
            }
        }

        # Resourceless request, show API information
        if ($request->resource == null) {
            $standard = array("name" => $this->name, "version" => $this->version);
            $this->response = new Response\OK\JSON($standard);
            return $this->response;
        }

        # Resource is not registered
        if (!isset($this->resources[$request->path])) {
            $this->response = new Response\NotFound();
            return $this->response;
        }

        $class = $this->resources[$request->path];

        # Registered but not implemented
        if (!class_exists($class)) {
            $this->response = new Response\NotImplemented();
            return $this->response;
        }

        try {
            $resource = new $class();
            $resource->request = $request;
            $method = strtolower($request->method);

            # Resource method not supported, i.e. "DELETE /users"
            if (!method_exists($resource, $method)) {
                throw new Exceptions\NotSupported();
            }

            # Content negotitation:
            switch ($request->headers["accept"]) {
                case "application/ld+json":
                    if ($resource instanceof \Sunnyvale\REST\Interfaces\LinkedData) {
                        $response = $resource->$method($request);
                        $response = $resource->toLinkedData($response);
                    } else {
                        $response = $resource->$method($request);
                    }
                    break;
                default:
                    # For the sake of development and testing. This is a sane default.
                    # Chrome sends text/html. If empty (curl) Apache2 sets it to */*
                    # Even though the clients Accept might be different from the response
                    # it is up to the client to determine if it was acceptable or not.
                    $response = $resource->$method($request);
            }
        } catch (Exceptions\NotFound $e) {
            $response = new Response\NotFound();
        } catch (Exceptions\NotSupported $e) {
            $response = new Response\NotImplemented();
        } catch (Exceptions\NotImplemented $e) {
            $response = new Response\NotImplemented();
        } catch (Exceptions\MissingParameter $e) {
            $response = new Response\UnprocessableEntity();
        } catch (Exceptions\NotAcceptable $e) {
            $response = new Response\NotAcceptable();
        }

        $this->response = $response;
        return $response;
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
