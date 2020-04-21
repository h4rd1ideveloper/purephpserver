<?php


namespace App\controllers;

use App\Helpers;
use App\model\User;
use Psr\Container\ContainerInterface;
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
    protected $container;

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
        $response->withStatus(200)->withAddedHeader('content-type', 'text/html')->getBody()->write('login');
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function listAll(Request $request, Response $response, $args): Response
    {
        $users = new User;
        $response
            ->withHeader('Content-Type', 'application/json')
            ->getBody()
            ->write(Helpers::toJson($users->skip($args['skip'] ?? 0)->take($args['limit'] ?? 100)->get()));
        return $response;
    }
}