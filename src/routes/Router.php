<?php

namespace App\routes;

use App\assets\lib\Helpers;
use App\http\HttpHelper;
use App\http\Request;
use App\http\Response;
use Closure;


/**
 * Class Router
 * @author Yan Santos Policar <policarpo@ice.ufjf.br>
 * @version 1.1.0
 * @see Closure
 * @see die
 * @see Request {@RequestInterface}
 * @see Response {@ResponseInterface}
 * @method get(string $string, Closure $param)
 * @method post(string $string, Closure $param)
 * @method patch(string $string, Closure $param)
 * @method put(string $string, Closure $param)
 * @method delete(string $string, Closure $param)
 */
class Router
{
    /**
     * @var array
     */
    private static $params = array();
    /**
     * @var string
     */
    private static $BASE_ULR;
    /**
     * @var array
     */
    private $routes = array();
    /**
     * @var array
     */
    private $INITIAL_STATE = array(
        "path_root" => '/universidade_rbm',
        "show_errors" => true,
        "cors" => true,
        "production_defines" => true
    );
    /**
     * @var Response
     */
    private $response;
    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $route;
    /**
     * @var Request
     */
    private $request;
    /**
     * Router constructor.
     * @param bool $config
     */
    public function __construct($config = false)
    {
        $this->init($config);
        $this->method = self::methodFromGlobal();
        $this->route = self::routeFromGlobal();
        $this->response = HttpHelper::responseFactory();
        $this->request = HttpHelper::requestFromGlobalsFactory($this->method, self::versionFromGlobal());
    }

    /**
     * @param $config
     */
    private function init($config)
    {
        switch ($config) {
            case false:
            {
                self::$BASE_ULR = $this->INITIAL_STATE["path_root"];
                $this->INITIAL_STATE["show_errors"] === true && Helpers::showErrors();
                $this->INITIAL_STATE["cors"] === true && Helpers::cors();
                $this->INITIAL_STATE["production_defines"] === true && Helpers::defines();
                break;
            }
            case is_array($config):
            {
                isset($config["path_root"]) &&
                is_string($config["path_root"]) &&
                self::$BASE_ULR = $config["path_root"];

                isset($config["show_errors"]) &&
                $config["show_errors"] === true &&
                Helpers::showErrors();

                isset($config["cors"]) &&
                $config["cors"] === true &&
                Helpers::cors();

                isset($config["cors"]) &&
                $config["cors"] === true &&
                Helpers::defines();
                break;
            }
            case is_string($config):
            {
                self::$BASE_ULR = $config;
                break;
            }
            default:
            {
                Helpers::defines();
                Helpers::cors();
                Helpers::showErrors();
                break;
            }
        }
    }

    /**
     * @return string
     */
    private static function methodFromGlobal()
    {
        return strtolower($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'get';
    }

    /**
     * @return mixed|string
     */
    private static function routeFromGlobal()
    {
        return isset($_SERVER['REQUEST_URI']) ?
            parse_url(str_replace(self::$BASE_ULR, "", $_SERVER['REQUEST_URI']), PHP_URL_PATH) :
            '/';
    }

    /**
     * @return mixed|string
     */
    private static function versionFromGlobal()
    {
        return isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';
    }

    /**
     * @param string $method
     * @param array $args
     * @return bool
     */
    public function __call($method, array $args)
    {
        $method = strtolower($method);
        if (!$this->validate($method)) {
            return false;
        }
        /**
         * To old Versions of the php
         * @deprecated $route = $args[0];$action = $args[1];
         * @see PHP 7.0 [$route, $action] = $args;
         */
        $route = $args[0];
        $action = $args[1];
        if (!isset($action) or !is_callable($action)) {
            return false;
        }
        $this->routes[$method][$route] = $action;
        return true;
    }

    /**
     * Allow method accept 'get', 'post', 'patch', 'put', 'delete'
     * @param string $method
     * @return bool
     */
    private function validate($method)
    {
        return in_array($method, array('get', 'post', 'patch', 'put', 'delete'));
    }

    /**
     * Closure Executor
     * RUN
     */
    public function run()
    {
        !isset($this->routes[$this->method]) && die(/**@Debugger */
        print_r(array('405 Method not allowed', $this->method, $this->route)));
        !isset($this->routes[$this->method][$this->route]) && die(/**@Debugger */
        print_r(array('404 Error', $this->method, $this->route)));
        self::$params = $this->getParams($this->method);
        die($this->routes[$this->method][$this->route]($this->request, $this->response));
    }

    /**
     *  Only to GET POST
     * @param string $method
     * @return mixed
     */
    private function getParams($method)
    {
        return strtolower($method) == 'get' ? $_GET : $_POST;
    }

    /**
     * Debugger Closures registered
     */
    public function debugger()
    {
        var_dump($this->routes);
    }
}
