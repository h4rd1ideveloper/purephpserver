<?php
//declare(strict_types=1); only php-7
require_once __DIR__ . '/vendor/autoload.php';

/***
 * Setup config
 */

use App\controller\AppController;
use App\http\Request;
use App\http\Response;
use App\routes\Dispatch;
use App\routes\Router;

$config = array(
    "path_root" => '/universidade_rbm',
    "show_erros" => true,
    "cors" => true,
    "production_defines" => true
);
$app = new Router($config);
$Dispatcher = new Dispatch(array(
    'index' => function (Request $req, Response $res) {
        $res->withHeader('Content-Type', 'application/json')->withStatus(201);
        return AppController::allAboutTheRequest($req);
    }
));
/**
 * Declare routes with closures her
 * First set a string to corresponding a router closure, then, set a closure that will be called inside die in {@Router}
 */
$app->get('/test', function (Request $req, Response $res) {
    $res->withStatus(200)->withHeader('Content-Type', 'application/json');
    return AppController::allAboutTheRequest($req);
});

/**
 * Test call Closure inside Dispatcher
 */
$app->get('/', $Dispatcher->getClosures('index'));
$app->post('/', $Dispatcher->getClosures('index'));
$app->patch('/', $Dispatcher->getClosures('index'));
$app->put('/', $Dispatcher->getClosures('index'));
$app->delete('/', $Dispatcher->getClosures('index'));


$app->run();
