<?php
require_once __DIR__ . './../controller/Controller.php';
require_once __DIR__ . './../model/User.php';

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
        return self::view('index');
    }

    /**
     *
     */
    public static function list() {
        $users = (new User)->listAll();
        return self::view('list', ['users' => $users]);
    }

    /**
     *
     */
    public static function write() {
        (new User)->createNew( self::params('user') );
        self::redirect('/list');
    }

    /**
     *
     */
    public static function logout() {
        (new User)->deleteAll();
        self::redirect('/');
    }
}