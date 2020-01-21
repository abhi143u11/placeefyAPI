<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;
error_reporting(1);
ini_set('display_errors', 1);
require '../vendor/autoload.php';
session_start();

// Instantiate the app
// $settings = require __DIR__ . '/../src/settings.php';
// $app = new \Slim\App($settings);


$app = new \Slim\App;
$container = new \Slim\Container;
// $container['cache'] = function () {
//     return new \Slim\HttpCache\CacheProvider();
// };
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app = new Slim\App([
    "settings"  => [
        "determineRouteBeforeAppMiddleware" => true,
    ]
]);
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});
// $container["csrf"] = function ($container) {
//     $guard = new \Slim\Csrf\Guard();
//     $guard->setPersistentTokenMode(true);
//     return $guard;
// };

// $app->add(new \Slim\HttpCache\Cache('public', 86400));
// $app->add(new \Slim\Csrf\Guard);
$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

// $container=$app->getContainer();
// $container['csrf'] = function ($c) {
//     return new \Slim\Csrf\Guard;
// };

//  require '../includes/constant.php';
//  require '../includes/dbconnect.php';
//  require '../includes/functions.php';
//  require '../includes/rest.php';
require_once('../app/api/customer.php');
require_once('../app/api/merchant.php');
require_once('../app/api/delivery_boy.php');
require_once('../app/api/orders.php');



$app->run();
