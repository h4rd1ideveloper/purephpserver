<?php

use Slim\Routing\RouteCollectorProxy;
//VIEWS
$app->group('/', function (RouteCollectorProxy $views) {
    //ROOT
    $views->get('', 'App\controllers\PageController::home');
    //POST ENTITY 
    $views->group('post/', function (RouteCollectorProxy $post) {
        $post->get('', 'App\controllers\PostController::post');
    });
    //AUTH
    $views->group('authentication/', function (RouteCollectorProxy $authentication) {
        $authentication->get('', 'App\controllers\PageController::loginAndSign');
    });
});
//API
$app->group('/api', function (RouteCollectorProxy $api) {
    $api->group('/user', function (RouteCollectorProxy $user) {
        $user->get('[/{skip:[0-9]+}[/{limit:[0-9]+}]]', 'App\controllers\UserControllerApi::all');
    });
    $api->group('/authentication', function (RouteCollectorProxy $authentication) {
        $authentication->post('/login', 'App\controllers\UserControllerApi::login');
        $authentication->post('/sign', 'App\controllers\UserControllerApi::sign');
    });
});
//RAW
//$app->get(
//    '/fn',
//    fn(Request $request, Response $response) => $response->withBody(
//        (new StreamFactory)->createStream(getenv('database'))
//    )
//);