<?php

namespace Sunnyexample\TEST;

class Rest_Client
{
    private $curl_client = null;
    private $url = null;

    public function __construct()
    {
        $this->curl_client = new Curl_Client();
        $this->url = "http://localhost:8080";
    }

    public function delete(string $resource)
    {
        $this->curl_client->method = "DELETE";
        $this->curl_client->url = $this->url . "/" . $resource;
        $this->curl_client->returntransfer = 0;
        return $this->curl_client->exec();
    }

    public function get(string $resource): array
    {
        $this->curl_client->method = "GET";
        $this->curl_client->url = $this->url . "/" . $resource;
        return $this->curl_client->exec();
    }

    public function post(string $resource, array $values): array
    {
        $this->curl_client->method = "POST";
        $this->curl_client->url = $this->url . "/" . $resource;
        $this->curl_client->postfields = $values;
        return $this->curl_client->exec();
    }

    public function put(string $resource, array $values): array
    {
        $this->curl_client->method = "PUT";
        $this->curl_client->url = $this->url . "/" . $resource;
        $this->curl_client->postfields = $values;
        return $this->curl_client->exec();
    }
}

class Curl_Client
{
    private $curl;
    public $method = "GET";
    public $url = null;
    public $returntransfer = 1;
    public $postfields = array();

    public function exec(): array
    {
        $this->curl = curl_init();
        $json = null;
        $this->setup();
        $result = curl_exec($this->curl);
        if ($this->returntransfer == 1) {
            $json = json_decode($result, true);
        }
        $code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        curl_close($this->curl);

        return array($json, $code);
    }

    private function setup()
    {
        if ($this->url == null) {
            throw new Exception("URL can not be null");
        }

        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, $this->returntransfer);

        if ($this->method == "POST") {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($this->postfields));
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        } elseif ($this->method == "PUT") {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($this->postfields));
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
        } elseif ($this->method == "DELETE") {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 0);
        }
    }
}
