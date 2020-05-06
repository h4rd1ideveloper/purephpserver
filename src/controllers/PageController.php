<?php


namespace App\controllers;

use App\lib\Helpers;
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
        $baseUrl = Helpers::baseURL();
        try {
            $response->getBody()->write(
                Helpers::Sender(
                    "dashboard",
                    null,
                    [
                        'header' => [
                            'title' => 'dashboad',
                            'keywords' => 'Dashboard ICARDO store ',
                            'bodyClass' => 'hold-transition sidebar-mini layout-fixed',
                            'stylesheet' => [
                                "$baseUrl/src/pages/plugins/fontawesome-free/css/all.min.css",
                                '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
                                "$baseUrl/src/pages/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css",
                                "$baseUrl/src/pages/plugins/icheck-bootstrap/icheck-bootstrap.min.css",
                                "$baseUrl/src/pages/plugins/jqvmap/jqvmap.min.css",
                                "$baseUrl/src/pages/dist/css/adminlte.min.css",
                                "$baseUrl/src/pages/plugins/overlayScrollbars/css/OverlayScrollbars.min.css",
                                "$baseUrl/src/pages/plugins/daterangepicker/daterangepicker.css",
                                "$baseUrl/src/pages/plugins/summernote/summernote-bs4.css",
                                '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700'
                            ]
                        ],
                        'footer' => ['admlt' => true]
                    ]
                )
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
            $response->getBody()->write(Helpers::Sender(
                "Login",
                [],
                ['footer' => ['admlt' => true]]
            )
            );
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
            $response->getBody()->write(Helpers::Sender(
                "Login",
                [],
                ['footer' => ['admlt' => true]]
            )
            );
        } catch (Exception $e) {
            $response->getBody()->write(sprintf("Internal server error%s %s", $e->getMessage(), $e->getTraceAsString()));
        }
        return $response;
    }
}
