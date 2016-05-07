<?php

set_include_path("./src");
require_once("control/router.php");
$router = new Router(dirname(getenv("SCRIPT_NAME")), dirname(getenv("SCRIPT_NAME")));
$router->main();
?>