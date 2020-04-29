<?php

use App\handlers\ErrorHandler;
use App\middleware\JsonBodyParserMiddleware;
use App\middleware\TrailingMiddleware;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;


$app->addRoutingMiddleware();
$app->addErrorMiddleware(
    true,
    true,
    true
)
    ->setErrorHandler(
        HttpNotFoundException::class,
        ErrorHandler::notFound(new Response())
    )->setErrorHandler(
        HttpMethodNotAllowedException::class,
        ErrorHandler::notAllowedMethod(new Response())
    );
$app->add(new TrailingMiddleware);
$app->add(new JsonBodyParserMiddleware);