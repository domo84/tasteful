<?php

require_once "../src/response.php";
require_once "../src/response/json.php";
require_once "../src/response/nocontent.php";
require_once "../src/response/notfound.php";
require_once "../src/response/options.php";
require_once "../src/response/conflict.php";
require_once "../src/response/created.php";
require_once "../src/response/notimplemented.php";
require_once "../src/response/unprocessableentity.php";

require_once "../src/resource.php";
require_once "../src/request.php";
require_once "../src/server.php";

require_once "../src/exceptions/notfound.php";
require_once "../src/exceptions/notimplemented.php";
require_once "../src/exceptions/notsupported.php";

require_once "tests/lib/traits.php";
require_once "tests/lib/rest_client.php";

require_once "database.php";
require_once "database/example.php";
require_once "database/example_item.php";
require_once "resources/examples.php";
require_once "resources/example_items.php";
