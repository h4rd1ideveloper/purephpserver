<?php


namespace App\controllers;

use App\lib\Components as View;
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
    public function post_form(Request $request, Response $response)
    {
        $response->getBody()->write(View::Sender('create-post', ['title' => 'Create a new POST']));
        return $response;
    }
}
