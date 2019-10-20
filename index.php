<?php
//declare(strict_types=1); only php-7
require_once __DIR__ . '/vendor/autoload.php';

/***
 * Setup config
 */

use App\http\Request;
use App\http\Response;
use App\routes\Dispatch;
use App\routes\Router;

$app = new Router('environment.production');
$Dispatcher = new Dispatch();
//Global Middleware
$app->middleware(
    array(
        function (Request $req, Response $res, Closure $next) {
            return $next($req, $res->withHeader('Content-Type', 'application/json'));
        },
        function (Request $req, Response $res, Closure $next) {
            return $next($req, $res->withHeader('Custom-Header', 'Yan'));
        }
    )
);
/**
 *
 */
$app->get('/', static function (Request $req) use ($app) {
    return Response::toJson($app->getMiddlewares());
});
$app->middleware(function (Request $request, Response $response, Closure $next) {
    $response->send("aqui");
    $next($request, $response->withStatus(201));
});
/**
 *
 */
$app->get('/debugger/env.production/string', static function (Request $req, Response $res) {
    $res
        ->send(
            Response::createCachingStreamOfLazyOpenStream('.env.production', 'r+')->getContents()
        );
});
$app->middleware(function (Request $request, Response $response, Closure $next) {
    //echo('/debugger/env.production/string middleware');
    $response->send('aqui');
    $next($request, $response->withStatus(203));
});
/**
 *
 */
$app->get('/debugger/env.production', static function (Request $req, Response $res) {
    $res
        ->send(
            Response::toJson(Response::getEnvFrom('.env.production'))
        );
});
/**
 *
 */
$app->run();
