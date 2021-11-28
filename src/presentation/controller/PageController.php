<?php


namespace App\presentation\controller;

use App\infra\lib\Helpers;
use App\lib\Components;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Class PageController
 * @package App\controllers
 */
class PageController
{
    use ControllerTrait;

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public static function beta(Request $request, Response $response, $args): Response
    {
        try {
            $response->getBody()->write(
                Components::render($args['view'])
            );
        } catch (Exception $e) {
            $response->getBody()->write(sprintf("Internal server error \n %s", Helpers::exceptionErrorMessage($e)));
        }
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public static function home(Request $request, Response $response, $args): Response
    {
        try {
            $response->getBody()->write(
                Components::render("dashboard")
            );
        } catch (Exception $e) {
            $response
                ->withStatus(500)
                ->getBody()
                ->write(sprintf("Internal server error \n %s", Helpers::exceptionErrorMessage($e)));
        }
        return $response;
    }

    /**
     * @param Response $response
     * @return Response
     */
    public static function loginAndSign(Response $response): Response
    {
        try {
            $response
                ->getBody()
                ->write(Components::render("authentication/Form", ['error' => false, 'fields' => ['-1']]));
        } catch (Exception $e) {
            $response
                ->withStatus(500)
                ->getBody()
                ->write(sprintf("Internal server error \n %s", Helpers::exceptionErrorMessage($e)));
        }
        return $response;
    }
}
