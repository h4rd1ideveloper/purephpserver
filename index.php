<?php
date_default_timezone_set("America/Sao_Paulo");

use App\lib\Helpers;
use DI\Container;
use Slim\Factory\AppFactory;

require_once(dirname(__FILE__) . '/vendor/autoload.php');

Helpers::setEnvByFile('.env');
AppFactory::setContainer(new Container);
$app = AppFactory::create();
$app->setBasePath(getenv('path_root') ?? (defined('path_root') ? path_root : ''));
$container = $app->getContainer();

require_once(dirname(__FILE__) . '/src/bootstrap/config.php');
require_once(dirname(__FILE__) . '/src/bootstrap/dependencies.php');
require_once(dirname(__FILE__) . '/src/bootstrap/middleware.php');
require_once(dirname(__FILE__) . '/src/bootstrap/routes.php');

$app->run();
