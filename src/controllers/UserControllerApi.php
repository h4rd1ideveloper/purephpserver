<?php


namespace App\controllers;

use App\lib\Helpers;
use App\model\User;
use App\repositories\PersonRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\MessageInterface;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;


/**
 * Class UserControllerApi
 * @package App\controllers
 */
class UserControllerApi
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * UserControllerApi constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return MessageInterface|Message|Response
     */
    public function login(Request $request, Response $response)
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

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return MessageInterface|Message
     */
    public function listAll(Request $request, Response $response, $args)
    {
        $users = (new PersonRepository(new User))
            ->setExcepted(['user_id', 'password'])
            ->pagination($args['skip'] ?? 0, $args['limit'] ?? 100)
            ->getRepo();
        $response = $response->withHeader('Content-Type', 'Application/json');
        $response->getBody()->write(Helpers::toJson($users));
        return $response;
    }
}