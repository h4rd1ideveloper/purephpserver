<?php

namespace App\controller;

use Lib\Helpers;
use Psr\Http\Message\HttpHelper;
use Psr\Http\Message\Request;
use Psr\Http\Message\Response;
use App\model\AjaxResolver;
use App\model\User;
use Exception;

/**
 * Class AppController
 * @see Controller
 */
final class AppController extends Controller
{
    public static $credentials = null;

    public function __construct()
    {
        self::$credentials = [
            'host' => DB_HOST,
            'user' => DB_USER,
            'pass' => DB_PASS,
            'name' => DB_NAME
        ];
    }

    /**
     * @param Response $res
     * @return void
     */
    public static function index(Response $res)
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            $user = self::userFactory();
            $login = explode('#|#', $_SESSION['user'])[0];
            $pass = explode('#|#', $_SESSION['user'])[1];
            $user = $user->findUser($login, $pass);
            if (count($user)) {
                $res->send(
                    [
                        'error' => false,
                        'user' => $user
                    ],
                    'pages/Home'
                );
            }
        } else {
            self::redirect('login');
        }
    }

    private static function userFactory(): User
    {
        return new User(self::$credentials);
    }

    /**
     * allAboutTheRequest
     * @Description All about content revice from request user
     * @param Request $req
     * @return string
     * @throws Exception
     */
    public static function allAboutTheRequest(Request $req)
    {
        return $req::toJson(
            [
                'body' => $req->getParsedBodyContent(),
                'params' => $req->getQueryParams(),
                'parsedBody' => $req->getParsedBody(),
            ]
        );
    }

    /**
     * @param Response $response
     */
    public static function dashboard(Response $response)
    {
        $login = explode('#|#', $_SESSION['user'])[0];
        $pass = explode('#|#', $_SESSION['user'])[1];
        $user = self::userFactory()->findUser($login, $pass);
        $response->send(
            [
                'error' => false,
                'user' => $user
            ],
            'pages/Home'
        );
    }

    /**
     * @return string
     */
    public static function apiUsers()
    {
        return Helpers::toJson((new AjaxResolver(true))::getAllUsers());
    }

    /**
     * @param Request $request
     * @return string
     */
    public static function apiUser(Request $request)
    {
        $id = isset($request->getQueryParams()['id']) ? $request->getQueryParams()['id'] : '';
        $user = (new AjaxResolver(true))::getUserById($id);
        $user = count($user) ? $user : ['error' => false, 'message' => "User not fount by id $id"];
        return Helpers::toJson(Helpers::stringIsOk($id) ? $user : ['error' => true, 'message' => 'missing id parameter', 'raw' => [$id, $request->getQueryParams()]]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function apiLogin(Request $request)
    {
        $body = HttpHelper::getBodyByMethod($request);
        if (
            isset($body['login'], $body['pass']) &&
            count(self::userFactory()->findUser($body['login'], $body['pass']))
        ) {
            session_start();
            $_SESSION['user'] = ($body['login'] . '#|#' . $body['pass']);
            self::redirect(Helpers::baseURL('home'));
        }
        return ['error' => true, 'message' => 'Missing parameters login and password', 'raw' => $body];
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function apiSign(Request $request)
    {
        $body = HttpHelper::getBodyByMethod($request);
        if (isset($body['login'], $body['pass'])) {
            return (
            (new AjaxResolver(true))
                ::CreateNewUser(
                    $body['login'],
                    $body['pass'],
                    $body['name'] ?: '',
                    $body['meta'] ?: ''
                )
            );
        }
        return ['error' => true, 'message' => 'Missing parameters name login and password', 'raw' => $body];
    }
}
