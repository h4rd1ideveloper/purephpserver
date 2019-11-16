<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

/***
 * Setup config
 */

use App\controller\AppController;
use Lib\Helpers;
use Psr\Http\Message\HttpHelper;
use Psr\Http\Message\Request;
use Psr\Http\Message\Response;
use Psr\Http\Message\Uri;

$app = HttpHelper::AppFactory('.env');
$app->get('/test/regex/:d', static function () {
    return new Request('', new Uri('/hello/{name}'), '', '', '');
});

//Views
$app->get('/', static function (Request $request, Response $response) {
    AppController::index($response);
});
$app->get('/home', static function (Request $request, Response $response) {
    AppController::dashboard($response);
});

$app->get('/login', static function (Request $request, Response $response) {
    AppController::view('pages/Login');
});
$app->post('/user/login', static function (Request $request, Response $response) {
    AppController::apiLogin($request);
});

/**
 *  EndPoint
 */
$app->post('/api/json/user/create', static function (Request $request) {
    return Helpers::toJson(AppController::apiSign($request));
});
///
$app->get('/api/json/user/login', static function (Request $request) {
    return AppController::apiLogin($request);
});

$app->run();


