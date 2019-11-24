<?php

namespace App\controller;

use App\Abstraction\UserAbstraction;
use App\model\User;
use App\view\components\Components;
use Closure;
use Exception;
use Lib\Factory;
use Lib\Helpers;
use Lib\Token;
use Psr\Http\Message\HttpHelper;
use Psr\Http\Message\Request;
use Psr\Http\Message\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AppController
 * @see Controller
 */
final class AppController extends Controller
{
    /**
     * @var array|null
     */
    public static $credentials = null;

    /**
     * AppController constructor.
     */
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
     * allAboutTheRequest
     * @Description All about content revice from request user
     * @return Closure
     */
    final public static function token(): Closure
    {
        return static function (Request $request): Response {
            $token = Token::encode(['id' => '1'], 'policarpo');
            [$headb64, $bodyb64, $cryptob64] = explode('.',$token);
            return new Response(
                200,
                HttpHelper::JSON_H,
                [
                    'token' => $token,
                    'decode' => Token::decodePiece($bodyb64),
                    'header' => Token::decodePiece($headb64),
                    $cryptob64
                ]
            );
        };
    }

    /**
     * allAboutTheRequest
     * @Description All about content revice from request user
     * @return Closure
     */
    final public static function allAboutTheRequest(): Closure
    {
        return static function (Request $request): Response {
            return new Response(200, HttpHelper::HTML5_h, HttpHelper::allAboutTheRequest($request));
        };
    }

    /**
     * @return Closure
     */
    public static function dashboard(): Closure
    {
        return static function (Request $request): Response {

            $Factory = new Factory(Factory::errorFactory('dashboard'));
            $User = $Factory::userFactory(self::$credentials);
            $body = $request::getBodyByMethod($request);
            return Helpers::isArrayAndHasKeys($body) || (is_array($body) && isset($body['login'], $body['pass'])) ?
                Factory
                    ::responseFactory(
                        302,
                        HttpHelper::HTML5_h,
                        Components::headerHTML(['title' => 'Dashboard'])
                            ::content(
                                HttpHelper::fileAsString(
                                    'pages/index',
                                    true,
                                    $User->findUser($Factory::UserAbstractionFactory($body))
                                )
                            )
                            ::footerHTML()
                    )
                :
                Factory::responseFactory(
                    301,
                    HttpHelper::JSON_H,
                    ['error' => true, 'message' => 'missing credentials']
                );
        };
    }


    /**
     * @return Closure
     * @throws Exception
     */
    public static function login(): Closure
    {
        $body = $_SESSION;
        if (
        Token::isValidToKey($body, '')
        ) {
            //session_start();
            //$user = Factory::userFactory(self::$credentials)->findUser(new UserAbstraction($body['login'], $body['pass']));
            $user = Token::decode($body, '');
            ['login' => $login, 'pass' => $pass, '_id' => $_id] = $user;
            self::redirect(Helpers::baseURL("dashboard?_id=$_id"));
        }
        return static function (Request $request): Response {
            return Factory::responseFactory(
                200,
                HttpHelper::HTML5_h,
                Components::headerHTML(['title' => 'Login'])
                    ::content(HttpHelper::fileAsString('pages/login', true))
                    ::footerHTML()
            );
        };
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public static function apiSign(Request $request): ResponseInterface
    {
        $body = HttpHelper::getBodyByMethod($request);
        if (
            Helpers::stringIsOk($body['login']) &&
            Helpers::stringIsOk($body['pass']) &&
            Helpers::stringIsOk($body['name'])
        ) {
            $user = new User(self::$credentials);
            $result = $user->createUser(
                (new UserAbstraction($body['login'], $body['pass']))
                    ->setName($body['name'])
                    ->setMeta($body['meta'])
            );
            return new Response(
                isset($result['error']) && $result['error'] ? 400 : 201,
                HttpHelper::JSON_H,
                $result
            );
        }
        return new Response(
            400,
            HttpHelper::JSON_H,
            ['error' => true, 'message' => 'Missing parameters name login and password', 'raw' => $body]
        );
    }
}
