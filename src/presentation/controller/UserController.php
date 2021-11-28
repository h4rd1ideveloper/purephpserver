<?php


namespace App\presentation\controller;

use App\domain\useCase\Authentication;
use App\infra\lib\Helpers;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;


/**
 * Class UserController
 * @package App\controllers
 */
class UserController extends Helpers
{
    use ControllerTrait;

    public function login(Request $request, Response $response): Response
    {
        $response->withHeader('Content-Type', 'Application/json');
        return self::tryCatch(static function () use ($response, $request) {
            ['user' => $user, 'password' => $password] = $request->getParsedBody();
            if (!$user || !$password) {
                $response
                    ->withStatus(400)
                    ->getBody()
                    ->write(self::toJson(['message' => 'parameter body and password is required']));
            }
            $response->withStatus(Authentication::login($user, $password ? 401 : 200));
        }, static function (Exception $exception) use ($response) {
            $response
                ->withStatus(500)
                ->getBody()
                ->write(self::exceptionErrorMessage($exception));
            return $response;
        });
    }


    /*    public function sign(Request $request, Response $response)
        {
            $body = $request->getParsedBody();
               if ($key = $body['user_key'] && $value = $body['user_value'] && $password = $body['password']) {
                     $password = password_hash($password, PASSWORD_DEFAULT);
                     $user = (new PersonRepository(new User))
                         ->setExcepted(['user_id'])
                         ->setRepo(User::where($key, $value)->get()->toArray());
                     $response = $response->withHeader('Content-Type', 'Application/json');
                     $response->getBody()->write(Helpers::toJson($user));
                 }
            return $response;
        }

        public function all(Request $request, Response $response, $args)
        {
            $users = (new PersonRepository(new User))
                ->setExcepted(['user_id', 'password'])
                ->pagination($args['skip'] ?? 0, $args['limit'] ?? 100)
                ->getRepo();
            $response
                ->withHeader('Content-Type', 'Application/json')
                ->getBody()
                ->write(Helpers::toJson($users));
            return $response;
        }*/
}