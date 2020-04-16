<?php


namespace App\handlers;


use App\Helpers;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
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
                $response->getBody()->write('not Found');
                return $response->withStatus(404);
            };
    }
}
