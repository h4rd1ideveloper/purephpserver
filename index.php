<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
use App\Helpers;


require(dirname(__FILE__) . './vendor/autoload.php');

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
// Set the Not Found Handler
$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
        $response = new GuzzleHttp\Psr7\Response();
        $response->getBody()->write('404 NOT FOUND');

        return $response->withStatus(404);
    }
);
// Set the Not Allowed Handler
$errorMiddleware->setErrorHandler(
    HttpMethodNotAllowedException::class,
    function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
        $response = new GuzzleHttp\Psr7\Response();
        $response->getBody()->write('405 NOT ALLOWED');

        return $response->withStatus(405);
    }
);
$app->add(
    function (Request $request, RequestHandler $handler) {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($path != '/' && substr($path, -1) == '/') {
            // recursively remove slashes when its more than 1 slash
            while (substr($path, -1) == '/') {
                $path = substr($path, 0, -1);
            }

            // permanently redirect paths with a trailing slash
            // to their non-trailing counterpart
            $uri = $uri->withPath($path);

            if ($request->getMethod() == 'GET') {
                $response = new GuzzleHttp\Psr7\Response();
                return $response
                    ->withHeader('Location', (string) $uri)
                    ->withStatus(301);
            } else {
                $request = $request->withUri($uri);
            }
        }

        return $handler->handle($request);
    }
);
// Define app routes
$app->get('/', function (Request $request, Response $response, $args) {
    $script = "
    <script type='text/javascript'>
    $('document').ready(() => {
            $('#toLogin,#toSign').on('click', function(e) {
                e.preventDefault();
                $('#login,#sign').toggleClass('d-none');
                let load = document.createElement('div');
                load.id = 'preloader';
                document.body.append(load);
                $('#preloader').length && $('#preloader').delay(80).fadeOut('slow', function() {
                    $(this).remove();
                });
            });   
    });    
</script>";
    $response->getBody()->write(Helpers::Sender("Login", [], ["footerMore" => $script]));
    return $response;
});
$app->get('/users', function (Request $request, Response $response) {
    $db = new App\Dao('localhost', 'root', '', 'development_db', 'mysql');
    $db->connect();
    $db->select('users', '*');
    $response->getBody()->write(json_encode($db->getResult(), JSON_UNESCAPED_UNICODE));
    return $response;
});
$app->post('/login', function (Request $request, Response $response) {
    $response->getBody()->write("Login Page");
    return $response;
});
// Run app
$app->run();
