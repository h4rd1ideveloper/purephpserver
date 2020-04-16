<?php


namespace App\middleware;

use App\Helpers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Middleware
{
    public static function trailing(Response $response): callable
    {
        return function (Request $request, RequestHandler $handler) use ($response) {
            $uri = $request->getUri();
            $path = $uri->getPath();
            if ($path != '/' && substr($path, -1) == '/' && substr(Helpers::baseURL(), -1) !== '/') {
                // recursively remove slashes when its more than 1 slash
                while (substr($path, -1) == '/') $path = substr($path, 0, -1);
                // permanently redirect paths with a trailing slash
                // to their non-trailing counterpart
                $uri = $uri->withPath($path);
                if ($request->getMethod() == 'GET') {
                    return $response
                        ->withStatus(301)
                        ->withHeader('Location', (string)$uri);
                } else {
                    $request = $request->withUri($uri);
                }
            }
            return $handler->handle($request);
        };
    }
}