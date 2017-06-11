<?php

class Curl_Client
{
	private $curl;
	public $method = "GET";
	public $url = null;
	public $returntransfer = 0;
	public $postfields = array();

	public function __construct()
	{
		$this->curl = curl_init();
	}

	public function exec(): array
	{
		$json = null;
		$this->setup();
		$result = curl_exec($this->curl);
		if($this->returntransfer == 1)
		{
			$json = json_decode($result);
		}
		$code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
		curl_close($this->curl);

		return array($json, $code);
	}

	private function setup()
	{
		if($this->url == null)
		{
			throw new Exception("URL can not be null");
		}

		curl_setopt($this->curl, CURLOPT_URL, $this->url);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, $this->returntransfer);

		if($this->method == "POST")
		{
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($this->postfields));
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		}
		else if($this->method == "PUT")
		{
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($this->postfields));
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
			curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
		}
		else if($this->method == "DELETE")
		{
			curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
		}
	}
}
