<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");


require_once __DIR__ . './../app/routes/index.php';
require_once __DIR__ . './../app/controller/AppController.php';

$app = new Router();

$app->post('/send', function () {
    return json_encode( AppController::readXLSXWriteHTML(), JSON_UNESCAPED_UNICODE );
});
$app->get('/', function (){
    return  AppController::index();
});
$app->post('/insert', function (){
    return json_encode( AppController::insert() , JSON_UNESCAPED_UNICODE );
});
$app->get('/teste', function(){
    return json_encode(array("ok"), JSON_UNESCAPED_UNICODE );
});
$app->run();
