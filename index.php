<?php
date_default_timezone_set("America/Sao_Paulo");

use App\lib\Helpers;
use App\main\Server;
use DI\Container;
use Slim\Factory\AppFactory;

require(__DIR__ . '/vendor/autoload.php');

Helpers::setEnvByFile('.env');
$container = new Container;

AppFactory::setContainer($container);

$app = AppFactory::create();

$server = new Server($app, $container);

$server->run();
