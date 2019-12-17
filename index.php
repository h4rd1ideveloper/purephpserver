<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use App\controller\AppController;
use Lib\Factory;
use Monolog\Logger;
use Psr\Http\Message\HttpHelper;
use Psr\Http\Message\Response;

try {
    /**
     * Router
     */
    $app = Factory::AppFactory('.env');

    //$app->get('/', AppController::index());

    $app->get('/dashboard', AppController::dashboard());

    $app->post('/token', AppController::token());

    $app->post('/login', AppController::login());

    $app->post('/sign', AppController::sign());
    $app->get('/', function () {
        return 'hello word';
    });
    $app->run();
} catch (Exception $e) {
    $message = $e->getMessage() . $e->getTraceAsString() . $e->getCode() . $e->getLine();
    try {
        $logger = new Logger('Runner');
        $logger->pushHandler(Factory::StreamHandlerFactory('Router_run.log', Logger::WARNING));
        $logger->critical('Fail to execute run Router ->' . $message);
    } catch (Exception $exception) {
        $message .= ". And, $exception";
    }
    HttpHelper::setHeader("HTTP/1.0 500 Internal Server Error $message");
    exit;
}
