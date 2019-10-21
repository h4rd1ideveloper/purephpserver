<?php
//declare(strict_types=1); only php-7
require_once __DIR__ . '/vendor/autoload.php';

/***
 * Setup config
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\controller\Controller;
use App\http\Request;
use App\http\Response;
use App\routes\Dispatch;
use App\routes\Router;

$app = new Router(array(
    'path_root' => '/portal',
    'cors' => true,
    'show_errors' => true,
    'production_defines' => true
));
$Dispatcher = new Dispatch();

$app->get('/view', function (Request $req, Response $res) use ($app) {
    Controller::view('pages/Listagem');
});
/**
 *
 */
$app->run();
