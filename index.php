<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '1024M');
require_once 'bootstrap/assets/config/const.php';
die(
print_r(
    [
        PRODUCTION_DB_NAME,
        PRODUCTION_DB_USER,
        PRODUCTION_DB_PASS,
        PRODUCTION_DB_TYPE,
        PRODUCTION_DB_HOST,
        ENEL_FIELDS,
        ENEL_TABLE
    ]
)
);
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
$app->post('/send', $routes['xlsxToHtml']);
$app->get('/', $routes['indexView']);
$app->post('/insert', $routes['insertToXlsx']);
$app->get('/teste', $routes['teste']);
$app->post('/consiliar', $routes['consiliar']);

$app->run();
