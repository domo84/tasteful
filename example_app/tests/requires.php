<?php

require_once "psr4-autoloader.php";

$loader = new Sunnyexample\Psr4AutoloaderClass();
$loader->register();

$loader->addNamespace("Tasteful", "../../src");
$loader->addNamespace("Sunnyexample", "../src");

require_once "tests/lib/traits.php";
require_once "tests/lib/rest_client.php";
