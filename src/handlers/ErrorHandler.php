<?php


namespace App\handlers;


use App\lib\Helpers;
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
                $baseUrl = Helpers::baseURL();
                $response->getBody()->write(
                    Helpers::Sender(
                        "404",
                        [],
                        [
                            'headerMore' => [
                                'admlt' =>
                                    [
                                        "$baseUrl/src/pages/plugins/fontawesome-free/css/all.min.css",
                                        "https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css",
                                        "$baseUrl/src/pages/dist/css/adminlte.min.css",
                                        "https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700",
                                        "$baseUrl/src/pages/css/style.css"
                                    ],
                                'bodyClass' => 'vh-100 w-100 d-flex flex-direction-row justify-content-center align-items-center'
                            ],
                            'footerMore' => ['admlt' => true]
                        ]
                    )
                );
                return $response->withStatus(404);
            };
    }

    public static function missingEnvironments(string $reason): Response
    {
        return new Response(500, null, (new StreamFactory)->createStream($reason));

    }
}
