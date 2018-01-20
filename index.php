<?php

require_once("vendor/autoload.php");

session_start();

use \Slim\Slim;

$app = new Slim();
$app->config('debug', true);

require_once("functions.php");
require_once("controllers/site.php");
require_once("controllers/admin-login.php");
require_once("controllers/admin-users.php");
require_once("controllers/admin-categories.php");
require_once("controllers/admin-products.php");

$app->run();
