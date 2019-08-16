<?php
require_once __DIR__. '/../controller/Controller.php';
require_once __DIR__. '/../model/User.php';
require_once __DIR__. '/../model/XLSXToHtmlParse.php';

/**
 * Class AppController
 * @see Controller
 */
final class AppController extends Controller {
    
    public static function index() {
        self::view('index');
    }
    public static function check() {
        self::view('result', ["find"=>(new XLSXToHtmlParse(true))->checkInTable_before(parent::request() )]);
    }
    public static function consiliar() {
        self::view('result', (new XLSXToHtmlParse(true))->checkInTable(parent::request() ));
    }
    public static function insert(){
        return (new XLSXToHtmlParse())->XLSXinsert(parent::request() );
    }
    public static function readXLSXWriteHTML() :void
    {
        self::view('list',
            (new XLSXToHtmlParse(true))->XLSXtoJSON( $_FILES["fileToUpload"]["tmp_name"] )
        );
    }
    public static function listTableToJson(){
        return (new XLSXToHtmlParse(true))->listTable();
    }
}