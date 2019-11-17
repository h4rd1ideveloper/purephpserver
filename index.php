<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use App\controller\AppController;
use Psr\Http\Message\HttpHelper;
use Psr\Http\Message\Request;
use Psr\Http\Message\Response;

$app = HttpHelper::AppFactory('.env');
/**
 *  Views
 */
$app->get('/', static function () {
    return new Response(200, ['Content-Type' => ['application/json']], ['error' => false, 'message' => 'ok']);
});
$app->get('/home', static function () {
    AppController::dashboard(new Response());
});
$app->get('/login', static function () {
    AppController::view('pages/Login');
});
$app->post('/user/login', static function (Request $request) {
    AppController::apiLogin($request);
});
/**
 *  EndPoint
 */
$app->post('/api/json/user/create', static function (Request $request): Response {
    return AppController::apiSign($request);
});
$app->get('/api/json/user/login', static function (Request $request) {
    return AppController::apiLogin($request);
});
/**
 * Resolver
 */
try {
    $app->run();
} catch (Exception $e) {
    $app->runException($e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL . $e->getCode() . PHP_EOL . $e->getLine());
}