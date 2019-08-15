<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '1024M');

die(print_r([
    getenv('PRODUCTION_DB_NAME' ),
    getenv('PRODUCTION_DB_USER' ),
    getenv('PRODUCTION_DB_PASS' ),
    getenv('PRODUCTION_DB_TYPE' ),
    getenv('PRODUCTION_DB_HOST'),
    getenv('ENEL_FIELDS'),
    getenv('ENEL_TABLE')
]));
/**
 * Setup imports
 */
require_once  './bootstrap/assets/lib/Helpers.php';
require_once  './bootstrap/routes/routes.php';
require_once  './bootstrap/routes/index.php';
/***
 * Setup config
 */
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
