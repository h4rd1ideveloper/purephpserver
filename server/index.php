<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

/***
 * Setup config
 */

use App\assets\lib\Helpers;
use App\routes\Dispatch;
use App\routes\Router;


Helpers::showErros();
Helpers::cors();
Helpers::const();
$app = new Router();
$Dispatch = new Dispatch();
/**
 * Declare routes with closures her
 */
$app->get('/', $Dispatch->get('test'));
$app->post('/', $Dispatch->get('test'));
$app->patch('/', $Dispatch->get('test'));
$app->put('/', $Dispatch->get('test'));
$app->delete('/', $Dispatch->get('test'));
$app->run();
