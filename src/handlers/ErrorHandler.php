<?php


namespace App\handlers;


use App\lib\Components;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;
use Throwable;

/**
 * Class ErrorHandler
 * @package App\handlers
 */
class ErrorHandler
{
    /**
     * @param Response $response
     * @return callable
     */
    public static function notAllowedMethod(Response $response): callable
    {
        return
            function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) use ($response) {
                $response->getBody()->write('405 NOT ALLOWED');
                return $response->withStatus(405);
            };
    }

    /**
     * @param Response $response
     * @return callable
     */
    public static function notFound(Response $response): callable
    {
        return
            function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) use ($response) {
                $response->getBody()->write(
                    Components::Sender("404")
                );
                return $response->withStatus(404);
            };
    }

    public static function missingEnvironments(string $reason): Response
    {
        return new Response(500, null, (new StreamFactory)->createStream($reason));

    }
}
