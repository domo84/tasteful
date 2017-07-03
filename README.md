# TASTEful

Base for RESTful web services

[![Build Status](https://travis-ci.org/domolicious/services.svg?branch=master)](https://travis-ci.org/domolicious/services)

## Usage

Take a look at the `example_app` for an implementation. But in essence this is what you would need.

```php
namespace Sunnyexample\Resources;

class Examples extends \Sunnyvale\REST\Resource
{
    public function get(\Sunnyvale\REST\Request $request)
    {
        return new \Sunnyvale\REST\Response\JSON(array(array("title" => "example#1")));
    }
}

class Example_Items extends \Sunnyvale\REST\Resource
{
    /** etc **/
}

$server = new \Sunnyvale\REST\Server($_SERVER);
$server->resources = [
    "examples" => "\Sunnyexample\Resources\Examples",
    "examples/items" => "\Sunnyexample\Resources\Example_Items"
];
$server->run();
$server->output();
```
