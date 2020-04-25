<?php


namespace App\controllers;


use App\lib\Helpers;
use Exception;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Class UserController
 * @package App\controllers
 */
class UserController
{
    protected ContainerInterface $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function loginPage(Request $request, Response $response, $args)
    {
        $script = /**@lang text */
            "
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
        try {
            $response->getBody()->write(Helpers::Sender("Login", [], ['footerMore' => ['scripts' => $script]]));
        } catch (Exception $e) {
            $response->getBody()->write(sprintf("Internal server error%s %s", $e->getMessage(), $e->getTraceAsString()));
        }
        return $response;
    }
}