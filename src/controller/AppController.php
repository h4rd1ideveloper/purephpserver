<?php

namespace App\controller;

use App\assets\lib\Dao;
use App\http\Request;

/**
 * Class AppController
 * @see Controller
 */
final class AppController extends Controller
{
    /**
     * @return string
     */
    public static function test()
    {
        return '';
    }

    /**
     * @param $params
     * @return void
     */
    public static function index($params)
    {
        self::view('index', $params);
    }

    public static function testQuery()
    {
        $_db = new Dao('localhost','root','','app');
        return $_db->delete('users',array('_id'=>2, 'name'=>'yan'));
    }

    /**
     * allAboutTheRequest
     * @Description All about content revice from request user
     * @param Request $req
     * @return string
     */
    public static function allAboutTheRequest(Request $req)
    {
        return $req::toJson(
            array(
                "body" => $req::jsonToArray($req->getBody()->getContents()),
                "params" => $req->getQueryParams(),
                "parsedBody" => $req->getParsedBody()
            )
        );
    }
}
