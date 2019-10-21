<?php

namespace App\controller;

use App\assets\lib\Dao;
use App\http\Request;
use App\model\AjaxResolver;

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
     * @param Request $request
     * @return array|bool|string
     */
    public static function listCondominiosBy(Request $request)
    {
        $body = $request->getParsedBody();
        if (isset($body['cpfcnpj'], $body['campo1'], $body['campo2'])) {
            $result = AjaxResolver::condominios($body['cpfcnpj'], '', '');
            return $result === false ?
                Request::toJson(array('error' => true, 'message' => 'something is worng inside AjaxResolver::condominios', 'raw' => array($result, $body))) :
                $result;
        }
        return Request::toJson(array('error' => true, 'message' => 'miss something in body request', 'raw' => $body));
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
     * @return mixed
     */
    public static function testQuery()
    {
        $_db = new Dao('localhost', 'root', '', 'app');
        return $_db->delete('users', array('_id' => 2, 'name' => 'yan'));
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
