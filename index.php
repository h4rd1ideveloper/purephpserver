<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '1024M');
/**
 * Setup imports
 */
require_once './bootstrap/assets/lib/Helpers.php';
require_once './bootstrap/routes/routes.php';
require_once './bootstrap/routes/index.php';
/***
 * Setup config
 */
Helpers::cors();
$app = new Router();
/**
 * setup routes closures
 */
$app->get('/send', $routes['xlsxToHtml']);
$app->get('/', $routes['indexView']);
$app->get('/insert', $routes['insertToXlsx']);
$app->get('/teste', $routes['teste']);
$app->get('/consiliar', $routes['consiliar']);

$app->run();
