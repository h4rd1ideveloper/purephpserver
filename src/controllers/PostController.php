<?php


namespace App\controllers;

use App\pages\components\Post;
use Exception;
use Psr\Http\Message\MessageInterface;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;


class PostController
{
    use ControllerTrait;

    /**
     * @param Request $request
     * @param Response $response
     * @return MessageInterface|Message|Response
     * @throws Exception
     */
    public function post(Request $request, Response $response)
    {
        $response->getBody()->write(Post::create());
        return $response;
    }
}
