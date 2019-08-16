<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '1024M');
ini_set('max_execution_time','600000');
/**
 * Setup imports
 */
require_once __DIR__. '/bootstrap/assets/lib/Helpers.php';
require_once __DIR__. '/bootstrap/routes/routes.php';
require_once __DIR__. '/bootstrap/routes/index.php';
/***
 * Setup config
 */
Helpers::cors();
$app = new Router();
/**
 * setup routes closures
 */
//$app->debugger();
$app->post('/send', $routes['xlsxToHtml']);
$app->get('/', $routes['indexView']);
$app->post('/insert', $routes['insertToXlsx']);
$app->get('/teste', $routes['teste']);
$app->post('/consiliar', $routes['consiliar']);
//$app->debugger();
$app->run();
//$app->debugger();
