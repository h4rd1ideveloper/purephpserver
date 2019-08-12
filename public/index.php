<?php
/**
 * Setup imports
 */

use App\assets\lib\Helpers\Helpers;

require_once __DIR__.'./../app/assets/lib/Helpers.php';
require_once __DIR__.'./../app/routes/routes.php';
require_once __DIR__ . './../app/routes/index.php';
/***
 * Setup config
 */
Helpers::cors();
$app = new Router();
/**
 * setup routes closures
 */
$app->post('/send', $routes['xlsxToHtml']);
$app->get('/',  $routes['indexView']);
$app->post('/insert', $routes['insertToXlsx']);
$app->get('/teste', $routes['teste']);

$app->run();
