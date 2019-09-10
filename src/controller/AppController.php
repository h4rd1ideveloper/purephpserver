<?php

namespace App\controller;

use App\http\HttpHelper;
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
