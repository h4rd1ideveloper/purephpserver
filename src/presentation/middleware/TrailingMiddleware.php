<?php


namespace App\middleware;


use App\lib\Helpers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseObject;

/**
 * Class TrailingMiddleware
 * @package App\middleware
 */
class TrailingMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return Response
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $uri = $request->getUri();
        $path = $uri->getPath();
        if ($path !== '/' && substr($path, -1) === '/' && substr(Helpers::baseURL(), -1) === '/') {
            while (substr($path, -1) === '/') {
                $path = substr($path, 0, -1);
            }
            $uri = $uri->withPath($path);
            if ($request->getMethod() === 'GET') {
                return (new ResponseObject)
                    ->withStatus(301)
                    ->withHeader('Location', (string)$uri);
            }
            return $handler->handle($request->withUri($uri));
        }
        return $handler->handle($request);
    }
}
