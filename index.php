<?php
/**
 * Setup imports
 */
require_once __DIR__ . './app/assets/lib/Helpers.php';
require_once __DIR__ . './app/routes/routes.php';
require_once __DIR__ . './app/routes/index.php';
/***
 * Setup config
 */
ini_set('memory_limit', '1024M');
Helpers::cors();
$app = new Router();
/**
 * setup routes closures
 */
$app->post('/send', $routes['xlsxToHtml']);
$app->get('/', $routes['indexView']);
$app->post('/insert', $routes['insertToXlsx']);
$app->get('/teste', $routes['teste']);
$app->post('/consiliar', $routes['consiliar']);

$app->run();