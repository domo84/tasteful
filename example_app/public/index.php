<?php

# require_once "../vendor/autoload.php";
require_once "../psr4-autoloader.php";

# due to inline classes not getting autoloaded
# cba to have a file per so small classes
require_once "../../src/exceptions.php";
require_once "../../src/response.php";

$loader = new Sunnyvale\Psr4AutoloaderClass();
$loader->register();

# in the real world ../src is covered by vendor/autoload.php
$loader->addNamespace("Sunnyvale\REST", "../../src");

$loader->addNamespace("Sunnyexample\Resources", "../resources");
$loader->addNamespace("Sunnyexample", "../");

$server = new Sunnyvale\REST\Server($_SERVER);
$server->resources = [
    "examples" => "\Sunnyexample\Resources\Examples",
    "examples/items" => "\Sunnyexample\Resources\Example_Items"
];

if ($server->run()) {
    $server->output();
}
