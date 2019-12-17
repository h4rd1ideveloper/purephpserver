<?php

namespace App\controller;

use App\Abstraction\UserAbstraction;
use App\model\User;
use App\view\components\Components;
use Closure;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Jenssegers\Date\Date;
use Lib\Factory;
use Lib\HttpHelper;
use Lib\Token;


/**
 * Class AppController
 * @see Controller
 */
final class AppController extends Controller
{

    /**
     * @return callable
     */
    public final static function token(): callable
    {
        ['SECRET' => $key] = HttpHelper::getEnvFrom('.env');
        return static function (ServerRequest $request) use ($key): Response {
            ['token' => $token] = HttpHelper::getBodyByMethod($request);
            [, $bodyb64,] = explode('.', $token);
            $payload = (array)Token::jsonDecode(Token::urlsafeB64Decode($bodyb64));
            $newPayload = array_merge(
                $payload,
                [
                    'exp' => Date::now()->addDay()->getTimestamp(),
                    'iat' => Date::now()->getTimestamp(),
                ]
            );
            if (Token::isValidByKey($token, trim($key))) {
                return self::Response(
                    [
                        'error' => false,
                        'raw' => Token::encode($newPayload, $key),
                        'message' => "valid Token $token",
                        'fields' => ['token']
                    ],
                    202,
                    HttpHelper::JSON_H
                );
            }
            return new Response(
                200,
                HttpHelper::JSON_H,
                ['error' => true, 'message' => "invalid Token to key: $key", 'fields' => ['token']]
            );
        };
    }

    /**
     * @param $content
     * @param int|null $status
     * @param array|null $headers
     * @return Response
     */
    private final static function Response($content, ?int $status = 200, ?array $headers = HttpHelper::HTML5_h): Response
    {
        $Factory = new Factory(Factory::errorFactory(__CLASS__ . '.Response'));
        return $Factory::responseFactory($status, $headers, $content);
    }

    /**
     * @return callable
     * @see Response
     */
    public final static function dashboard(): callable
    {
        /*$Factory = new Factory(Factory::errorFactory(__CLASS__ . '.dashboard'));
        ['SECRET' => $key] = HttpHelper::getEnvFrom('.env');
        if (!isset($_SESSION['user']) || empty($_SESSION['user']) || !Token::isValidByKey($_SESSION['user'], $key)) {
            self::redirect('');
        }
        [$userSchema, $userModel] = [
            $Factory::userAbstractionFactory(['username' => '', 'password' => '']), $Factory::userFactory()
        ];*/
        return static function (): Response {

            return Factory
                ::responseFactory(
                    200,
                    HttpHelper::HTML5_h,
                    Components::headerHTML(['title' => 'Dashboard'])
                        ::content(
                            HttpHelper::fileAsString(
                                'pages/index',
                                true,
                                []
                            )
                        )
                        ::footerHTML()
                );
        };
    }

    /**
     * @return callable
     */
    public final static function login(): callable
    {
        return static function (ServerRequest $request): Response {
            $body = HttpHelper::getBodyByMethod($request);
            $raw = ['error' => true, 'raw' => $body];
            if (isset($body, $body['username'], $body['password'])) {
                [$userSchema, $userModel] = [new UserAbstraction($body['username'], $body['password']), new User()];
                if ($userModel->existUser($userSchema)) {
                    //$context = (new User())->findUser($userSchema);
                    self::redirect('dashboard');
                }
                return self::Response(
                    Components
                        ::headerHTML(['title' => 'Login'])
                        ::content(HttpHelper::fileAsString('pages/Authenticate', true, array_merge($raw, ['message' => 'username'])))
                        ::footerHTML(), 401,
                    HttpHelper::HTML5_h
                );
            }
            return self::Response(
                Components
                    ::headerHTML(['title' => 'Login'])
                    ::content(
                        HttpHelper
                            ::fileAsString(
                                'pages/Authenticate',
                                true, array_merge($raw, ['message' => 'username, password', 'fields' => ['username', 'password']])
                            )
                    )
                    ::footerHTML(),
                406,
                HttpHelper::HTML5_h
            );
        };
    }

    /**
     * @return callable
     */
    public final static function sign(): callable
    {
        $Factory = new Factory(Factory::errorFactory(__CLASS__ . '.sign'));
        return function (ServerRequest $request) use ($Factory): Response {
            $body = HttpHelper::getBodyByMethod($request);
            $raw = ['error' => true, 'raw' => $body];
            if (isset($body['username'], $body['password'])) {
                [$userSchema, $userModel] = [$Factory::userAbstractionFactory($body), $Factory::userFactory()];
                /**
                 * username already exists ?
                 */
                if ($userModel->existUser($userSchema)) {
                    $context = array_merge($raw, ['fields' => 'username', 'message' => 'username already exists']);
                    /**
                     * Send HTML Response with context Error
                     */
                    return self::Response(
                        self::htmlAsString(['title' => 'Sign'], 'pages/Authenticate', true, $context),
                        406,
                        HttpHelper::HTML5_h
                    );
                }
                /**
                 * Try create user on Database
                 */
                ['error' => $error, 'message' => $msg] = $userModel->createUser($userSchema);
                if ($error) {
                    /**
                     * Reload to same page without context Error @todo [Handler Mysql Error ]
                     */
                    return self::Response(
                        array_merge($raw, ['message' => "Fail to create user: $msg"]),
                        500,
                        HttpHelper::JSON_H
                    );
                }
                /**
                 * Everything is Ok
                 */
                session_create_id();
                session_start();
                $env = HttpHelper::getEnvFrom('.env');
                /**
                 * put JWT token on Session
                 * sub (subject) = Entidade à quem o token pertence, normalmente o ID do usuário;
                 * iss (issuer) = Emissor do token;
                 * exp (expiration) = Timestamp de quando o token irá expirar;
                 * iat (issued at) = Timestamp de quando o token foi criado;
                 * aud (audience) = Destinatário do token, representa a aplicação que irá usá-lo.
                 */
                $id = $userModel->userId($userSchema);
                $_SESSION['user'] = Token::encode(
                    [
                        'sub' => $id,
                        'iss' => 'app',
                        'exp' => Date::now()->addDay()->getTimestamp(),
                        'iat' => Date::now()->getTimestamp(),
                        'aud' => 'app',
                        'username' => $userSchema->getUsername()
                    ]
                    ,
                    $env['SECRET']
                );
                /** Reload to Dashboard */
                self::redirect('dashboard');
                //return self::Response(array_merge($userModel->findUser($userSchema), [$_SESSION['user'], $env['SECRET']]), 200, HttpHelper::JSON_H);
            }
            $context = array_merge(
                $raw, ['fields' => 'username, password, email, first_name, last_name, tel', 'message' => 'empty body']
            );
            /**
             * Send HTML Response with context Error
             */
            return self::Response(
                self::htmlAsString(['title' => 'Sign'], 'pages/Authenticate', true, $context),
                400,
                HttpHelper::HTML5_h
            );
        };
    }

    /**
     * @param array|null $title
     * @param string|null $pathFile
     * @param bool $ob
     * @param array $context
     * @return string
     */
    private static function htmlAsString(?array $title = ['title' => ''], ?string $pathFile = '', bool $ob = true, array $context = []): string
    {
        return Components::headerHTML($title)
            ::content(HttpHelper::fileAsString($pathFile, $ob, $context))
            ::footerHTML();
    }

    /**
     * @return Closure
     */
    public static function index()
    {
        return function (): Response {
            return self::Response(
                self::htmlAsString(
                    ['title' => 'Login/Sign'],
                    'pages/Authenticate',
                    true,
                    ['error' => true, 'fields' => ['lucas', 'santos']]
                ),
                200,
                array_merge(HttpHelper::HTML5_h, ["Set-Cookie" => "HttpOnly;Secure;SameSite=Strict"])
            );
        };
    }
}
