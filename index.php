<?php
date_default_timezone_set("America/Sao_Paulo");

use App\lib\Helpers;
use DI\Container;
use Slim\Factory\AppFactory;

require(dirname(__FILE__) . '/vendor/autoload.php');

Helpers::setEnvByFile('.env');
AppFactory::setContainer(new Container);
$app = AppFactory::create();
$app->setBasePath('/purephpserver');
$container = $app->getContainer();

require(dirname(__FILE__) . '/src/config.php');
require(dirname(__FILE__) . '/src/dependencies.php');
require(dirname(__FILE__) . '/src/middleware.php');
require(dirname(__FILE__) . '/src/routes.php');

$app->run();
