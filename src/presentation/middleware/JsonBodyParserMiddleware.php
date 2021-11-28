<?php

namespace App\presentation\middleware;

use App\infra\lib\Helpers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * Class JsonBodyParserMiddleware
 * @package App\middleware
 */
class JsonBodyParserMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return Response
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        return Helpers::tryCatch(static function () use ($handler, $request) {
            $contentType = $request->getHeaderLine('Content-Type');
            if (strpos($contentType, 'application/json') !== false) {
                $contents = json_decode(
                    file_get_contents('php://input'),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
                if (json_last_error() === JSON_ERROR_NONE) {
                    $request = $request->withParsedBody($contents);
                }
            }
            return $handler->handle($request);
        }, $handler->handle($request));
    }
}