<?php


namespace App\controllers;

use App\lib\Components;
use Exception;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Class PageController
 * @package App\controllers
 */
class PageController
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    // constructor receives container instance

    /**
     * PageController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public static function home(Request $request, Response $response, $args)
    {
        try {
            $response->getBody()->write(
                Components::sender("dashboard")
            );
        } catch (Exception $e) {
            $response->getBody()->write(
                sprintf(
                    "Internal server error%s %s",
                    $e->getMessage(),
                    $e->getTraceAsString()
                )
            );
        }
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public static function login(Request $request, Response $response, $args)
    {
        try {
            $response->getBody()->write(Components::sender("Login"));
        } catch (Exception $e) {
            $response->getBody()->write(sprintf("Internal server error%s %s", $e->getMessage(), $e->getTraceAsString()));
        }
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public static function sign(Request $request, Response $response, $args)
    {
        try {
            $response->getBody()->write(Components::sender("Login"));
        } catch (Exception $e) {
            $response->getBody()->write(sprintf("Internal server error%s %s", $e->getMessage(), $e->getTraceAsString()));
        }
        return $response;
    }
}
