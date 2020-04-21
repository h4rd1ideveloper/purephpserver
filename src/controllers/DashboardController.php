<?php


namespace App\controllers;


use App\Helpers;
use Exception;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class DashboardController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function home_1(Request $request, Response $response, $args)
    {
        $baseUrl = Helpers::baseURL();
        try {
            $response->getBody()->write(
                Helpers::Sender(
                    "dashboard_1",
                    [],
                    [
                        'headerMore' => ['admlt' => "<!-- Font Awesome -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/fontawesome-free/css/all.min.css'>
                        <!-- Ionicons -->
                        <link rel='stylesheet' href='//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'>
                        <!-- Tempusdominus Bbootstrap 4 -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css'>
                        <!-- iCheck -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/icheck-bootstrap/icheck-bootstrap.min.css'>
                        <!-- JQVMap -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/jqvmap/jqvmap.min.css'>
                        <!-- Theme style -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/dist/css/adminlte.min.css'>
                        <!-- overlayScrollbars -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/overlayScrollbars/css/OverlayScrollbars.min.css'>
                        <!-- Daterange picker -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/daterangepicker/daterangepicker.css'>
                        <!-- summernote -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/summernote/summernote-bs4.css'>
                        <!-- Google Font: Source Sans Pro -->
                        <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700' rel='stylesheet'>
                      ", 'bodyClass' => 'hold-transition sidebar-mini layout-fixed'],
                        'footerMore' => ['admlt' => true]
                    ]
                )
            );
        } catch (Exception $e) {
            $response->getBody()->write(
                sprintf("Internal server error%s %s", $e->getMessage(), $e->getTraceAsString())
            );
        }
        return $response;
    }

    public static function home_2(Request $request, Response $response, $args)
    {
        $baseUrl = Helpers::baseURL();
        try {
            $response->getBody()->write(
                Helpers::Sender(
                    "dashboard_2",
                    [],
                    [
                        'headerMore' => ['admlt' => "<!-- Font Awesome -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/fontawesome-free/css/all.min.css'>
                        <!-- Ionicons -->
                        <link rel='stylesheet' href='//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'>
                        <!-- Tempusdominus Bbootstrap 4 -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css'>
                        <!-- iCheck -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/icheck-bootstrap/icheck-bootstrap.min.css'>
                        <!-- JQVMap -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/jqvmap/jqvmap.min.css'>
                        <!-- Theme style -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/dist/css/adminlte.min.css'>
                        <!-- overlayScrollbars -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/overlayScrollbars/css/OverlayScrollbars.min.css'>
                        <!-- Daterange picker -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/daterangepicker/daterangepicker.css'>
                        <!-- summernote -->
                        <link rel='stylesheet' href='$baseUrl/src/pages/plugins/summernote/summernote-bs4.css'>
                        <!-- Google Font: Source Sans Pro -->
                        <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700' rel='stylesheet'>
                      ", 'bodyClass' => 'hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed'],
                        'footerMore' => ['admlt' => true]
                    ]
                )
            );
        } catch (Exception $e) {
            $response->getBody()->write(
                sprintf("Internal server error%s %s", $e->getMessage(), $e->getTraceAsString())
            );
        }
        return $response;
    }

    public static function home_3(Request $request, Response $response, $args)
    {
        try {
            $response->getBody()->write(
                Helpers::Sender(
                    "dashboard_3",
                    [],
                    [
                        'headerMore' => ['admlt' => true, 'bodyClass' => 'hold-transition sidebar-mini'],
                        'footerMore' => ['admlt' => true]
                    ]
                )
            );
        } catch (Exception $e) {
            $response->getBody()->write(
                sprintf("Internal server error%s %s", $e->getMessage(), $e->getTraceAsString())
            );
        }
        return $response;
    }
}