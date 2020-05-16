<?php


use App\controllers\handlers\ErrorHandler;
use App\middleware\JsonBodyParserMiddleware;
use App\middleware\TrailingMiddleware;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;


$app->addRoutingMiddleware();
$app->addErrorMiddleware(
    true,
    true,
    true
)
    ->setErrorHandler(
        HttpNotFoundException::class,
        ErrorHandler::notFound()
    )->setErrorHandler(
        HttpMethodNotAllowedException::class,
        ErrorHandler::notAllowedMethod()
    );
$app->add(new TrailingMiddleware);
$app->add(new JsonBodyParserMiddleware);