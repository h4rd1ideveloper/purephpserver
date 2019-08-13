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
    public static function consiliar(){
        return (new XLSXToHtmlParse())->checkInTable(parent::request() );
    }
    public static function insert(){
        return (new XLSXToHtmlParse())->XLSXinsert(parent::request() );
    }
    public static function readXLSXWriteHTML() :void
    {
        self::view('list', (new XLSXToHtmlParse('suporteRBM02','RBMsuporte03','crefazscm_webscm','mysql','','177.184.16.61'))->XLSXtoJSON( $_FILES["fileToUpload"]["tmp_name"] ) );
    }
    public static function listTableToJson(){
        return (new XLSXToHtmlParse())->listTable();
    }
}