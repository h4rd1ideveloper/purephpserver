<?php

require_once __DIR__ . '.\..\app\routes\index.php';
require_once __DIR__ . '.\..\app\controller\AppController.php';

$app = new Router();
/** @var TYPE_NAME $app */
$app->get('/', function () {
    return AppController::index();
});
/** @var TYPE_NAME $app */
$app->get('/list', function () {
    return AppController::list();
});
/** @var TYPE_NAME $app */
$app->post('/list', function () {
    return AppController::write();
});
/** @var TYPE_NAME $app */
$app->get('/out', function () {
    return AppController::logout();
});
//echo __DIR__;
$app->run();