<?php
require_once  './../controller/Controller.php';
require_once  './../model/User.php';
require_once  './../model/XLSXToHtmlParse.php';

/**
 * Class AppController
 * @see Controller
 */
final class AppController extends Controller {
    
    public static function index() {
        self::view('index');
    }
    public static function consiliar(){
        return (new XLSXToHtmlParse())->checkInTable(parent::request() );
    }
    public static function insert(){
        return (new XLSXToHtmlParse())->XLSXinsert(parent::request() );
    }
    public static function readXLSXWriteHTML() :void
    {
        self::view('list', (new XLSXToHtmlParse(true))->XLSXtoJSON( $_FILES["fileToUpload"]["tmp_name"] ) );
    }
    public static function listTableToJson(){
        return (new XLSXToHtmlParse())->listTable();
    }
}