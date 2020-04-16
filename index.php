<?php

use App\handlers\ErrorHandler;
use GuzzleHttp\Psr7\Response;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;

require(dirname(__FILE__) . './vendor/autoload.php');
require(dirname(__FILE__) . './src/constantes.php');
$app = AppFactory::create();
$app->setBasePath('/' . sub_path);

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    ErrorHandler::notFound(new Response())
);
$errorMiddleware->setErrorHandler(
    HttpMethodNotAllowedException::class,
    ErrorHandler::notAllowedMethod(new Response())
);
$app->add(App\middleware\Middleware::trailing(new Response()));
/**Views Routes */
//$app->get('/', 'App\controllers\UserController::loginPage');
$app->get('/', 'App\controllers\DashboardController::home_1');

/**Api methods */
$app->get('/users', 'App\controllers\UserControllerApi::listAll');
$app->post('/login', 'App\controllers\UserControllerApi::login');
/** Run app */
$app->run();
