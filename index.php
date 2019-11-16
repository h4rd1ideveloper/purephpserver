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

$app = HttpHelper::AppFactory('.env');

//Views
$app->get('/', static function (Request $request) {
    //AppController::index($response);
    return new Response(200, ['Content-Type' => 'application/json'], ['error' => false, 'message' => 'ok']);
});
$app->get('/home', static function (Request $request) {
    AppController::dashboard(new Response());
});

$app->get('/login', static function (Request $request) {
    AppController::view('pages/Login');
});
$app->post('/user/login', static function (Request $request) {
    AppController::apiLogin($request);
});

/**
 *  EndPoint
 */
$app->post('/api/json/user/create', static function (Request $request) {
    return Helpers::toJson(AppController::apiSign($request));
});

$app->get('/api/json/user/login', static function (Request $request) {
    return AppController::apiLogin($request);
});

$app->run();


