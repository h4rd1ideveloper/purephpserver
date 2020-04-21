<?php

use App\handlers\ErrorHandler;
use App\Helpers;
use App\middleware\JsonBodyParserMiddleware;
use App\middleware\TrailingMiddleware;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;

require(dirname(__FILE__) . './vendor/autoload.php');

Helpers::setEnvByFile('.env');
Helpers::setupIlluminateConnectionAsGlobal();
$app = AppFactory::create();
$app->setBasePath(getenv('root_path'));
$app->addRoutingMiddleware();
$app
    ->addErrorMiddleware(
        true,
        true,
        true
    )
    ->setErrorHandler(
        HttpNotFoundException::class,
        ErrorHandler::notFound(new Response())
    )->setErrorHandler(
        HttpMethodNotAllowedException::class,
        ErrorHandler::notAllowedMethod(new Response())
    );
$app->add(new TrailingMiddleware);
$app->add(new JsonBodyParserMiddleware);


$app->get('/', 'App\controllers\DashboardController::home_1');
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
$app->run();
