<?php
//declare(strict_types=1); only php-7
require_once __DIR__ . '/vendor/autoload.php';

/***
 * Setup config
 */

use App\controller\AppController;
use App\controller\Controller;
use App\http\Request;
use App\routes\Dispatch;
use App\routes\Router;

try {

    $routerConfig = array(
        'path_root' => '/portal',
        'cors' => true,
        'show_errors' => true,
        'production_defines' => true
    );
    $app = new Router($routerConfig);
    $Dispatcher = new Dispatch();

    $app->get('/', static function () {
        Controller::view('pages/Listagem');
    });

    $app->post('/api/comdominios', static function (Request $request) {
        return AppController::listCondominiosBy($request);
    });
    /**
     *
     */
    $app->run();
} catch (Exception $e) {
    echo 'Fail to init Router Server', $e->getMessage(), $e->getCode(), $e->getTraceAsString();
}

