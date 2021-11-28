<?php


namespace App\presentation\handler;


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
     * @return callable
     */
    public static function notAllowedMethod(): callable
    {
        return static function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
            $response = new Response(405);
            $response->getBody()->write('405 NOT ALLOWED');
            return $response;
        };
    }

    /**
     * @return callable
     */
    public static function notFound(): callable
    {
        return static fn(ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) => (new Response)
            ->withStatus(404)
            ->getBody()
            ->write(Components::render("404"));
    }

    public static function missingEnvironments(string $reason): Response
    {
        return new Response(500, null, (new StreamFactory)->createStream($reason));
    }
}
