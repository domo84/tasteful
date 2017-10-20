<?php

# require_once "../vendor/autoload.php";
require_once "../psr4-autoloader.php";

$loader = new Sunnyexample\Psr4AutoloaderClass();
$loader->register();

# in the real world Sunnyvale\REST is covered by vendor/autoload.php
$loader->addNamespace("Sunnyvale\REST", "../../src");
$loader->addNamespace("Sunnyexample", "../src");

$server = new Sunnyvale\REST\Server($_SERVER);
$server->resources = [
    "examples" => "\Sunnyexample\Resources\Examples",
    "examples/items" => "\Sunnyexample\Resources\ExampleItems"
];

$result = $server->run();
$server->output();
