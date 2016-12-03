<?php

class Response
{
	public $body = null;
	public $headers = array();
}

class JsonResponse extends Response
{
	public function __construct($body)
	{
		$this->body = $this->isJson($body) ? $body : json_encode($body);
		$this->headers[] = "Content-Type: application/json; charset=utf-8";
		$this->headers[] = "Content-Length: " . strlen($this->body);
	}

	private function isJson($content)
	{
		if(is_array($content))
		{
			return false;
		}

		if(is_object($content))
		{
			return false;
		}

		$pos = strpos($content, "{");
		if($pos == 1 || $pos == 2)
		{
			return true;
		}
		return false;
	}
}
