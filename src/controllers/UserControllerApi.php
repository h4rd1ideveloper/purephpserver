<?php


namespace App\controllers;


use App\Helpers;
use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;

class UserControllerApi
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function login(Request $request, Response $response): Response
    {
        $response->withStatus(200)->withAddedHeader('content-type', 'text/html')->getBody()->write('login');
        return $response;
    }

    public function listAll(Request $request, Response $response): Response
    {
        /* $db = new Dao('localhost', 'root', '', 'development_db', 'mysql');
         $db->connect();
         $db->select('users', '*');
         $response->getBody()->write(json_encode($db->getResult(), JSON_UNESCAPED_UNICODE));*/

            $response->getBody()->write('listAll');

        return $response;
    }
}