<?php
require_once __DIR__ . './../controller/Controller.php';
require_once __DIR__ . './../model/User.php';
require_once __DIR__ . './../model/XLSXToHtmlParse.php';

/**
 * Class AppController
 * @see Controller
 */
final class AppController extends Controller {
    /**
     *
     */
    public static function index() {
        //echo json_encode( Router::getRequest() );
        self::view('index');
    }
    public static function insert(){
        return (new XLSXToHtmlParse())->XLSXinsert(parent::request() );
    }
    public static function readXLSXWriteHTML()
    {
        return (new XLSXToHtmlParse())->XLSXtoJSON( $_FILES["fileToUpload"]["tmp_name"] );
    }
}