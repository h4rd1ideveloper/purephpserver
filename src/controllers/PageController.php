<?php


namespace App\controllers;

use App\lib\Components;
use App\lib\Helpers;
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
                Components::sender($args['view'])
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
                Components::sender("dashboard")
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
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public static function loginAndSign(Request $request, Response $response, $args): Response
    {
        try {
            $response->getBody()->write(Components::sender("authentication/Form", ['error' => false, 'fields' => ['-1']]));
        } catch (Exception $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(sprintf("Internal server error \n %s", Helpers::exceptionErrorMessage($e)));
        }
        return $response;
    }
}
