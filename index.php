<?php
error_reporting(E_ALL | E_STRICT);
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
