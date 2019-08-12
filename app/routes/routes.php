<?php
/**
 * Setup imports
 */
require_once __DIR__ . './../model/XLSXToHtmlParse.php';
require_once __DIR__ . './../controller/AppController.php';
/**
 * Setup Object routes closures
 */
$routes = [
    'xlsxToHtml' =>
        function () {
            return json_encode(AppController::readXLSXWriteHTML(), JSON_UNESCAPED_UNICODE);
        },
    'indexView' =>
        function () {
            return AppController::index();
        },
    'insertToXlsx' =>
        function () {
            return json_encode(AppController::insert(), JSON_UNESCAPED_UNICODE);
        },
    'teste' =>
        function () {
            return json_encode(AppController::listTableToJson(), JSON_UNESCAPED_UNICODE);
        }
];