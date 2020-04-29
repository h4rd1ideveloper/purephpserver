<?php


namespace App\controllers;

use App\lib\Helpers;
use App\model\User;
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

    // constructor receives container instance

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
     * @return Response
     */
    public function login(Request $request, Response $response): Response
    {
        $response->withStatus(200)
            ->withAddedHeader('content-type', 'text/html')
            ->getBody()
            ->write('login');
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
        $users = (new User)->getUsersFields($args['skip'] ?? 0, $args['limit'] ?? 100);
        $response = $response->withHeader('Content-Type', 'Application/json');
        $response->getBody()->write(Helpers::toJson($users));
        return $response;
    }
}