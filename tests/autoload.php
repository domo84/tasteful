<?php

require_once "src/Traits/JSONTools.php";

require_once "src/Response.php";
require_once "src/Response/Conflict.php";
require_once "src/Response/Created.php";
require_once "src/Response/NoContent.php";
require_once "src/Response/NotAcceptable.php";
require_once "src/Response/NotFound.php";
require_once "src/Response/NotImplemented.php";
require_once "src/Response/OK.php";
require_once "src/Response/OK/JSON.php";
require_once "src/Response/OK/LinkedData.php";
require_once "src/Response/Options.php";
require_once "src/Response/Unauthorized.php";
require_once "src/Response/UnprocessableEntity.php";

require_once "src/Request.php";
require_once "src/Server.php";

require_once "src/Exceptions/MissingParameter.php";
require_once "src/Exceptions/NotAcceptable.php";
require_once "src/Exceptions/NotFound.php";
require_once "src/Exceptions/NotImplemented.php";
require_once "src/Exceptions/NotSupported.php";

require_once "src/Interfaces/LinkedData.php";
require_once "src/Interfaces/Resource.php";

require_once "tests/lib/resources.php";
