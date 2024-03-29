<?php

namespace App\main;


use App\infra\lib\Helpers;
use App\infra\service\security\Logger;
use App\main\router\Router;
use App\middleware\TrailingMiddleware;
use App\presentation\handler\ErrorHandler;
use App\presentation\middleware\JsonBodyParserMiddleware;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Slim\App;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;

class Server extends Router
{
    private App $app;
    private Container $container;

    public function __construct(App $app, Container $container)
    {
        $this->setContainer($container)
            ->setApp($app)
            ->dbSettings()
            ->illuminateDb()
            ->setupMiddleware()
            ->getApp()
            ->setBasePath(environments('path_root'));
        parent::__construct($this->getApp());
    }

    /**
     * @return App
     */
    public function getApp(): App
    {
        return $this->app;
    }

    /**
     * @param App $app
     * @return Server
     */
    public function setApp(App $app): Server
    {
        $this->app = $app;
        return $this;
    }

    private function setupMiddleware(): Server
    {
        $this
            ->app
            ->add(new TrailingMiddleware)
            ->add(new JsonBodyParserMiddleware)
            ->addRoutingMiddleware();
        $this
            ->app
            ->addErrorMiddleware(true, true, true)
            ->setErrorHandler(HttpNotFoundException::class, ErrorHandler::notFound())
            ->setErrorHandler(HttpMethodNotAllowedException::class, ErrorHandler::notAllowedMethod());
        return $this;
    }

    private function illuminateDb(): Server
    {
        $this->container->set('illuminate_db', fn(Container $c) => Helpers::setupIlluminateConnectionAsGlobal($c->get('db_settings')));
        Helpers::tryCatch(
            fn() => $this->container->get('illuminate_db'),
            function (Exception $exception) {
                $payload = [
                    'db_settings' => Helpers::tryCatch(/**
                     * @throws DependencyException
                     * @throws NotFoundException
                     */ fn() => $this->container->get('db_settings'), '-')
                ];
                $message = Helpers::exceptionErrorMessage($exception);
                Logger::errorLog($message, 'tryCatch_get_illuminate_db', $payload);
                die($message);
            }
        );
        return $this;
    }

    private function dbSettings(): Server
    {
        $this->container->set('db_settings', fn() => [
            'driver' => environments('driver', 'mysql'),
            'host' => environments('host', 'localhost'),
            'port' => environments('port', '3306'),
            'database' => environments('database', 'webapp'),
            'username' => environments('username', 'root'),
            'password' => environments('password'),
            'charset' => environments('charset', 'utf8'),
            'collation' => environments('collation', 'utf8_unicode_ci'),
        ]);
        return $this;
    }

    /**
     * @return Container|null
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @param Container|null $container
     * @
     * return Server
     */
    public function setContainer(?Container $container): Server
    {
        $this->container = $container;
        return $this;
    }

    public function run(): void
    {
        $this->app->run();
    }

}