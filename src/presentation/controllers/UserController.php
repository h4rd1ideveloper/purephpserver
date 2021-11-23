<?php


namespace App\presentation\controllers;

use App\data\model\User;
use App\domain\useCase\Authentication;
use App\lib\Helpers;
use mysql_xdevapi\Exception;
use Psr\Http\Message\MessageInterface;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;


/**
 * Class UserController
 * @package App\controllers
 */
class UserController extends Helpers
{
    use ControllerTrait;

    /**
     * @param Request $request
     * @param Response $response
     * @return MessageInterface|Message|Response
     */
    public function login(Request $request, Response $response)
    {
        try {
            $body = $request->getParsedBody();
            $response->withHeader('Content-Type', 'Application/json');
            if (!($user = $body['user']) || !($password = $body['password'])) {
                $response->withStatus(400)->getBody()->write(self::toJson(['message' => 'parameter body and password is required']));
            } else if (!count($userLogged = Authentication::login($user, $password))) {
                $response->withStatus(401)->getBody()->write(self::toJson([]));
            } else {
                $response->getBody()->write(self::toJson(['data' => $userLogged]));
            }
        } catch (Exception $exception) {
            $response->withStatus(500)->getBody()->write(self::exceptionErrorMessage($exception));
        }
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return MessageInterface|Message|Response
     */
    public function sign(Request $request, Response $response)
    {
        $body = $request->getParsedBody();
        /*     if ($key = $body['user_key'] && $value = $body['user_value'] && $password = $body['password']) {
                 $password = password_hash($password, PASSWORD_DEFAULT);
                 $user = (new PersonRepository(new User))
                     ->setExcepted(['user_id'])
                     ->setRepo(User::where($key, $value)->get()->toArray());
                 $response = $response->withHeader('Content-Type', 'Application/json');
                 $response->getBody()->write(Helpers::toJson($user));
             }*/
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return MessageInterface|Message
     */
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
    }
}