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

$jsonMiddleware = function () {
    return function (Request $request, Response $response, Closure $closure) {
        $response->withHeader('Content-Type', 'application/json');
        $closure($request, $response);
    };
};
try {
    $routerConfig = array(
        'path_root' => '/portal',
        'cors' => true,
        'show_errors' => true,
        'production_defines' => true
    );
    $app = new Router($routerConfig);
    $Dispatcher = new Dispatch();

    //Views
    $app->get('/', static function (Request $request, Response $response) {
        AppController::index($response);
    });
    $app->post('/boletos', static function (Request $request, Response $response) {
        AppController::indexAfterPost($request, $response);
    });
    $app->get('/api/json', static function () {
        return /**@lang JSON */ '{"key":"value"}';
    });

    ///EndPoint API
    $app->patch('/api/contratos/atualizar', static function (Request $request, Response $response) {
        return AppController::atualizarContratos($request, $response);
    });
    $app->post('/api/boletos', static function (Request $request, Response $response) {
        return AppController::apiIndex($request);
    });
    $app->get('/api/relatorio', static function (Request $request, Response $response) {
        AppController::relatorio($request);
    });
    $app->post('/api/comdominios', static function (Request $request) {
        return AppController::listCondominiosBy($request);
    });

    $app->run();
} catch (Exception $e) {
    echo 'Fail to init Router Server: MSG', $e->getMessage(), 'CODE:', $e->getCode(), 'TRACE:', $e->getTraceAsString();
}

