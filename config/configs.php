<?php

$db_host = $_ENV["DB_HOST"];
$db_name = $_ENV["DB_NAME"];
$db_user = $_ENV["DB_USER"];
$db_pass = $_ENV["DB_PASS"];

define("DB_HOST", $db_host);
define("DB_NAME", $db_name);
define("DB_USER", $db_user);
define("DB_PASS", $db_pass);

define("DIR_LOGS", __DIR__ . "/logs/");

$arr_requests = [
    "get" => [],
    "post" => []
];
