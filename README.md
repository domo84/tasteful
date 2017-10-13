# TASTEful

Base for RESTful web services

[![Build Status](https://travis-ci.org/domolicious/tasteful.svg?branch=master)](https://travis-ci.org/domolicious/tasteful)

## Usage

Take a look at the `example_app` for an implementation. But in essence this is what you would need.

```php
<?php /* index.php */

namespace Sunnyexample\Resources;

require "vendor/autoload.php";

class Examples extends \Sunnyvale\REST\Resource
{
    public function get(\Sunnyvale\REST\Request $request)
    {
        return new \Sunnyvale\REST\Response\JSON(array(array("title" => "example#1")));
    }
}

class Example_Items extends \Sunnyvale\REST\Resource
{
    /* public function delete,get,post,put */
}

$server = new \Sunnyvale\REST\Server($_SERVER);
$server->resources = [
    "examples" => "\Sunnyexample\Resources\Examples",
    "examples/items" => "\Sunnyexample\Resources\Example_Items"
];
$server->run();
$server->output();
```

Start a web server `php -S localhost:8080` and 

```bash
curl localhost:8080/examples
[{"title": "example#1"}]
```
