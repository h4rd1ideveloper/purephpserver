<?php

use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;

$app->group('/', function (RouteCollectorProxy $views) {
    $views->get('',
        'App\controllers\PageController::loginAndSign'
    );
});
//$app->get(
//    '/fn',
//    fn(Request $request, Response $response) => $response->withBody(
//        (new StreamFactory)->createStream(getenv('database'))
//    )
//);
$app->group('/api', function (RouteCollectorProxy $api) {
    $api->group('/user', function (RouteCollectorProxy $user) {
        $user->get(
            '[/{skip:[0-9]+}[/{limit:[0-9]+}]]',
            'App\controllers\UserControllerApi::all'
        );
    });
    $api->group('/authentication', function (RouteCollectorProxy $authentication) {
        $authentication->post(
            '/login',
            'App\controllers\UserControllerApi::login'
        );
        $authentication->post(
            '/sign',
            'App\controllers\UserControllerApi::sign'
        );
    });
});