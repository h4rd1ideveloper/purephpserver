<?php

namespace App\controller;

use App\Abstraction\Token\Token;
use App\Abstraction\UserAbstraction;
use App\model\User;
use App\view\components\Components;
use Closure;
use Exception;
use Lib\Factory;
use Lib\Helpers;
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
     * allAboutTheRequest
     * @Description All about content revice from request user
     * @return Closure
     */
    public static function token(): Closure
    {
        return static function (Request $request): Response {
            $token = Token::encode(['id' => '1'], 'policarpo');
            [$headb64, $bodyb64, $cryptob64] = explode('.', $token);
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
     * @return callable
     */
    public static function dashboard(): callable
    {
        return static function (Request $request): Response {

            $Factory = new Factory(Factory::errorFactory('dashboard'));
            $User = $Factory::userFactory();
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
     * @param Request $request
     * @return Closure
     */
    public static function login(): Closure
    {
        return static function (Request $request): Response {
            $body = HttpHelper::getBodyByMethod($request);
            if (isset($body, $body['username'], $body['password'])) {
                [$userSchema, $userModel] = [new UserAbstraction($body['username'], $body['password']), new User()];
                $d = $userModel->existUser($userSchema) ?
                    (new User())->findUser(new UserAbstraction($body['username'], $body['password']))['password'] :
                    [];
            }
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
                    ->setFirstName($body['name'])
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
