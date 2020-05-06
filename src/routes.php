<?php

use Slim\Routing\RouteCollectorProxy;

$app->group('/', function (RouteCollectorProxy $views) {
    $views->get('',
        'App\controllers\PageController::home'
    );
    $views->get('login',
        'App\controllers\PageController::login'
    );
    $views->get('sign',
        'App\controllers\PageController::sign'
    );
});

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