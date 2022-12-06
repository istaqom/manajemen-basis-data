<?php

use App\Models\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

include_once "../src/api/user.php";
include_once "../src/api/driver.php";
include_once "../src/api/merchant.php";
include_once "../src/api/store.php";
include_once "../src/api/store_menu.php";
include_once "../src/api/orders.php";

$app->run();
