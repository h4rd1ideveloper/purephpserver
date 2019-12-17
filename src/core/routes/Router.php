<?php

namespace Server;

use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Lib\Helpers;
use Lib\HttpHelper;

/**
 * Class Router
 * @package Server
 * @author Yan Santos Policar <policarpo@ice.ufjf.br>
 * @version 1.1.0
 * @see callable
 * @see die
 * @see Request
 * @see Response
 * @method get(string $string, callable $param)
 * @method post(string $string, callable $param)
 * @method patch(string $string, callable $param)
 * @method put(string $string, callable $param)
 * @method delete(string $string, callable $param)
 */
class Router
{

    /**
     * @var array
     */
    const INITIAL_STATE = [
        "path_root" => '',
        "show_errors" => true,
        "cors" => true
    ];
    const methods = ['get', 'post', 'patch', 'put', 'delete'];
    /**
     * @var string
     */
    private static $BASE_ULR;
    /**
     * @var array
     */
    private $routes = [];
    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $route;
    /**
     * @var ServerRequest
     */
    private $request;

    /**
     * Router constructor.
     * @param bool $config
     * @throws Exception
     */
    public function __construct($config = false)
    {
        $this->init($config);
        $this->request = HttpHelper::requestFromGlobalsFactory(self::routeFromGlobal());
        $this->method = strtolower($this->request->getMethod());
        $this->route = $this->request->getUri()->getPath();
    }

    /**
     * @param $config
     */
    private function init($config)
    {
        if (!$config) {
            self::$BASE_ULR = $this::INITIAL_STATE["path_root"];
            $this::INITIAL_STATE["show_errors"] === true && Helpers::showErrors();
            $this::INITIAL_STATE["cors"] === true && Helpers::cors();
        } elseif (is_array($config)) {
            isset($config["path_root"]) && is_string($config["path_root"]) &&
            self::$BASE_ULR = $config["path_root"];
            isset($config["show_errors"]) && $config["show_errors"] === true &&
            Helpers::showErrors();
            isset($config["cors"]) && $config["cors"] === true &&
            Helpers::cors();
        } elseif (is_string($config)) {
            $envs = HttpHelper::getEnvFrom($config);
            self::$BASE_ULR = HttpHelper::stringIsOk($envs['path_root']) ? $envs['path_root'] : self::$BASE_ULR;
            isset($envs['show_errors']) && $envs['show_errors'] === true &&
            Helpers::showErrors();
            isset($envs['cors']) && $envs['cors'] === true &&
            Helpers::cors();
            if (isset($envs['DB_type'], $envs['DB_HOST'], $envs['DB_USER'], $envs['DB_PASS'], $envs['DB_NAME'])) {
                define("DB_type", $envs['DB_type']);
                define("DB_HOST", $envs['DB_HOST']);
                define("DB_USER", $envs['DB_USER']);
                define("DB_PASS", $envs['DB_PASS']);
                define("DB_NAME", $envs['DB_NAME']);
            }
        } else {
            Helpers::cors();
            Helpers::showErrors();
        }
    }

    /**
     * @return mixed|string
     */
    private static function routeFromGlobal()
    {
        return isset($_SERVER['REQUEST_URI']) ?
            HttpHelper::requestUriString(self::$BASE_ULR, $_SERVER['REQUEST_URI'], true) :
            '/';
    }


    /**
     * @param string $method
     * @param array $args
     * @return boolean
     */
    public function __call($method, array $args)
    {
        $method = strtolower($method);
        if (!$this->validate($method)) {
            return false;
        } elseif (isset($args[0], $args[1])) {
            [$route, $action] = $args;
            if (isset($action, $route) && is_callable($action)) {
                $this->routes[$method][$route] = $action;
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Allow method accept 'get', 'post', 'patch', 'put', 'delete'
     * @param string $method
     * @return bool
     */
    private function validate($method)
    {
        return in_array($method, self::methods);
    }


    /**
     * Executor
     * RUN
     * @throws Exception
     */
    public function run()
    {
        $this->validateMethodOrFail();
        $route = $this->validateRouteOrFail();
        die($this->routes[$this->method][$route]($this->request));
    }

    /**
     *
     */
    private function validateMethodOrFail()
    {
        try {
            !isset($this->routes[$this->method]) &&
            die(new Response(
                405,
                ['Content-Type' => 'application/json'],
                ['405 Method not allowed', $this->method, $this->route]
            ));
        } catch (Exception $e) {
            die(function () use ($e) {
                HttpHelper::setHeader('HTTP/1.0 405 Method Not Allowed');
                return 'Method Not Allowed ' . $e->getMessage();
            });
        }
    }

    /**
     * @return string
     */
    private function validateRouteOrFail(): string
    {
        $alternativeRoute = substr($this->route, 0, (strlen($this->route) - 1));
        try {
            !isset($this->routes[$this->method][$this->route]) &&
            !isset($this->routes[$this->method][$alternativeRoute]) &&
            die(new Response(
                404,
                ['Content-Type' => 'application/json'],
                ['404 Error', $this->method, $this->route, $alternativeRoute]
            ));
        } catch (Exception $e) {
            die(function () use ($e) {
                HttpHelper::setHeader('HTTP/1.0 404  Request Route Not Found');
                return 'Route Not Found ' . $e->getMessage();
            });
        }
        return isset($this->routes[$this->method][$this->route]) ? $this->route : $alternativeRoute;
    }

}
