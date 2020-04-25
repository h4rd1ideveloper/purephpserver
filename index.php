<?php

use App\lib\Helpers;
use DI\Container;
use Slim\Factory\AppFactory;

require(dirname(__FILE__) . './vendor/autoload.php');

Helpers::setEnvByFile('.env');
AppFactory::setContainer(new Container);
$app = AppFactory::create();
$app->setBasePath(getenv('root_path') ?? '/server');
$container = $app->getContainer();


require(dirname(__FILE__) . './src/config.php');
require(dirname(__FILE__) . './src/middleware.php');
require(dirname(__FILE__) . './src/routes.php');

$app->run();