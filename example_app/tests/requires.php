<?php

require_once "../src/Response.php";
require_once "../src/Response/JSON.php";
require_once "../src/Response/NoContent.php";
require_once "../src/Response/NotFound.php";
require_once "../src/Response/Options.php";
require_once "../src/Response/Conflict.php";
require_once "../src/Response/Created.php";
require_once "../src/Response/NotImplemented.php";
require_once "../src/Response/UnprocessableEntity.php";

require_once "../src/Resource.php";
require_once "../src/Request.php";
require_once "../src/Server.php";

require_once "../src/Exceptions/NotFound.php";
require_once "../src/Exceptions/NotImplemented.php";
require_once "../src/Exceptions/NotSupported.php";
require_once "../src/Exceptions/MissingParameter.php";

require_once "../src/Interfaces/Resource.php";

require_once "tests/lib/traits.php";
require_once "tests/lib/rest_client.php";

require_once "src/Database/SQLite.php";
require_once "src/Database/Example.php";
require_once "src/Database/ExampleItem.php";
require_once "src/Resources/Examples.php";
require_once "src/Resources/ExampleItems.php";
