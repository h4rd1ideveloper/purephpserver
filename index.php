<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

/***
 * Setup config
 */

use App\controller\AppController;
use Lib\Helpers;
use Psr\Http\Message\Request;
use Psr\Http\Message\Response;
use Psr\Http\Message\Uri;
use Server\Dispatch;
use Server\Router;

try {
    $app = new Router('.env');
    $Dispatcher = new Dispatch();
    $Dispatcher
        ->setMiddleware(
            static function (Request $request, Response $response, Closure $next) {
                if (!isset($_SESSION['user'])) {
                    $next(
                        $request,
                        $response
                            ->withStatus(307)
                            ->withHeader('Location', Helpers::baseURL('login')),
                        $next
                    );
                }
                $next($request, $response, $next);
            }, 'authentication'
        )
        ->setMiddleware(
            static function (Request $request, Response $response, Closure $next) {
                $next($request, $response->withHeader('Content-Type', 'application/json'), $next);
            }, 'json'
        );
    $app->get('/test/regex/:d', static function () {
        return new Request('', new Uri('/hello/{name}'), '', '', '');
    });

    //Views
    $app->get('/', static function (Request $request, Response $response) {
        AppController::index($response);
    });
    $app->get('/home', static function (Request $request, Response $response) {
        AppController::dashboard($response);
    }, $Dispatcher->getMiddleware('authentication'));

    $app->get('/login', static function (Request $request, Response $response) {
        AppController::view('pages/Login');
    });
    $app->post('/user/login', static function (Request $request, Response $response) {
        AppController::apiLogin($request);
    });


    /**
     *  EndPoint
     */
    $app->get('/api/json/users', static function () {
        return AppController::apiUsers();
    }, $Dispatcher->getMiddleware('json'));
    ///
    $app->get('/api/json/user', static function (Request $request) {
        return AppController::apiUser($request);
    }, $Dispatcher->getMiddleware('json'));
    ////
    ////
    $app->post('/api/json/user/create', static function (Request $request) {
        return Helpers::toJson(AppController::apiSign($request));
    }, $Dispatcher->getMiddleware('json'));
    ///
    $app->get('/api/json/user/login', static function (Request $request) {
        return AppController::apiLogin($request);
    }, $Dispatcher->getMiddleware('json'));

    $app->run();
} catch (Exception $e) {
    echo 'Fail to init Router Server: MSG', $e->getMessage(), 'CODE:', $e->getCode(), 'TRACE:', $e->getTraceAsString();
}

