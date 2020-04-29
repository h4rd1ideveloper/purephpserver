<?php

use App\model\User;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;


$app->get('/', 'App\controllers\DashboardController::home_1');
$app->get('/function', function (Request $request, Response $response) use ($container) {

    return $response;
});

$app->get('/login', 'App\controllers\UserController::loginPage');

$app->group('/api', function (RouteCollectorProxy $api) {

    $api->group('/user', function (RouteCollectorProxy $user) {
        $user->get('[/{skip:[0-9]+}[/{limit:[0-9]+}]]', 'App\controllers\UserControllerApi::listAll');
    });

    $api->group('/authentication', function (RouteCollectorProxy $authentication) {
        $authentication->post('/login', 'App\controllers\UserControllerApi::login');
        $authentication->post('/sign', 'App\controllers\UserControllerApi::login');
    });
});