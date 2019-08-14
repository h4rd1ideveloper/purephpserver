<?php
/**
 * Setup imports
 */
require_once  './../model/XLSXToHtmlParse.php';
require_once  './../controller/AppController.php';
/**
 * Setup Object routes closures
 */
$routes = [
    'xlsxToHtml' =>
        function (): void {
            AppController::readXLSXWriteHTML();
        },
    'indexView' =>
        function () {
            return AppController::index();
        },
    'insertToXlsx' =>
        function (): string {
            return json_encode(AppController::insert(), JSON_UNESCAPED_UNICODE);
        },
    'consiliar' =>
        function(){
            return json_encode(AppController::consiliar(), JSON_UNESCAPED_UNICODE);
        },
    'teste' =>
        function () {
            var_dump(AppController::listTableToJson());
        }
];