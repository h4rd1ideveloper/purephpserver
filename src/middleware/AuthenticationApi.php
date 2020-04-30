<?php


namespace App\middleware;


use App\lib\Token;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseObject;
use function DI\get;

class AuthenticationApi implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return Response
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!Token
            ::isValidByKey(
                $request->getHeaderLine('Authentication-token'),
                get('key')
            )
        ) {
            $uri = $request->getUri()->withPath("/");
            if ($request->getMethod() == 'GET') {
                return (new ResponseObject)
                    ->withStatus(301)
                    ->withHeader('Location', (string)$uri);
            } else {
                $request = $request->withUri($uri);
            }
        }
        return $handler->handle($request);
    }
}