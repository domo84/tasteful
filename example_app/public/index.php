<?php

# require_once "../vendor/autoload.php";
require_once "../psr4-autoloader.php";

$loader = new Sunnyexample\Psr4AutoloaderClass();
$loader->register();

# in the real world Tasteful is covered by vendor/autoload.php
$loader->addNamespace("Tasteful", "../../src");
$loader->addNamespace("Sunnyexample", "../src");

$server = new Tasteful\Server($_SERVER);
$server->resources = [
    "examples" => "\Sunnyexample\Resources\Examples",
    "examples/items" => "\Sunnyexample\Resources\ExampleItems"
];

$result = $server->run();
$server->output();
