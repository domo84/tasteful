# TASTEful

Base for RESTful web services

[![Build Status](https://travis-ci.org/domolicious/tasteful.svg?branch=master)](https://travis-ci.org/domolicious/tasteful)

## Usage

Take a look at the `example_app` for an implementation. But in essence this is what you would need.

```php
<?php /* index.php */

namespace Sunnyexample\Resources;

use Sunnyvale\REST\Request;
use Sunnyvale\REST\Response;

require "vendor/autoload.php";

class Examples implements \Sunnyvale\REST\Interfaces\Resource
{
    public function delete(Request $request): Response
    {
        return new Response\NoContent();
    }
    
    public function get(Request $request): Response
    {
        return new Response\JSON([["title" => "example#1"]]);
    }
    
    public function post(Request $request): Response
    {
        return new Response\JSON(["title" => "example#1"]);
    }
    public function put(Request $request): Response
    {
        return new Response\JSON(["title" => "example#1"]);
    }
}

class ExampleItems implements \Sunnyvale\REST\Interfaces\Resource
{
    /* public function delete,get,post,put */
}

$server = new \Sunnyvale\REST\Server($_SERVER);
$server->resources = [
    "examples" => "\Sunnyexample\Resources\Examples",
    "examples/items" => "\Sunnyexample\Resources\ExampleItems"
];
$server->run();
$server->output();
```

Start a web server `php -S localhost:8080` and 

```bash
curl localhost:8080/examples
[{"title": "example#1"}]
```
