<?php

namespace App\routes;

use App\assets\lib\Helpers;
use App\http\HttpHelper;
use App\http\Request;
use App\http\Response;
use Closure;
use Exception;
use InvalidArgumentException;


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
 * @method middleware(Closure|array $param)
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
        "path_root" => '/somepath',
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
     * @var array
     */
    private $middlewares = array();
    /**
     * @var array
     */
    private $currentCallReference = null;

    /**
     * Router constructor.
     * @param bool $config
     * @throws Exception
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
            case 'environment.production':
            {
                $envs = HttpHelper::getEnvFrom('.env.production');
                self::$BASE_ULR = HttpHelper::stringIsOk($envs['path_root']) ? $envs['path_root'] : self::$BASE_ULR;
                isset($envs['show_errors']) && $envs['show_errors'] === true && Helpers::showErrors();
                isset($envs['cors']) && $envs['cors'] === true && Helpers::cors();
                isset($envs['production_defines']) && $envs['production_defines'] === true && Helpers::defines();
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
        return isset($_SERVER['REQUEST_METHOD']) && Helpers::stringIsOk($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'get';
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
     * @return mixed|string
     */
    private static function versionFromGlobal()
    {
        return isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';
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
        }
        if ($method === 'middleware') {
            $actionMiddleware = $args[0];
            if (is_array($actionMiddleware)) {
                foreach ($actionMiddleware as $middlewareFunction) {
                    if (!is_callable($middlewareFunction)) {
                        throw new InvalidArgumentException("Pass a array of Closures in method middleware");
                    } elseif ($this->currentCallReference !== null) {
                        $ref_method = $this->currentCallReference[0];
                        $ref_route = $this->currentCallReference[1];
                        $this->setMiddleware($middlewareFunction, $ref_method, $ref_route);
                    } else {
                        $this->setMiddleware($middlewareFunction);
                    }
                }
            } elseif ($this->currentCallReference !== null) {
                $ref_method = $this->currentCallReference[0];
                $ref_route = $this->currentCallReference[1];
                $this->setMiddleware($actionMiddleware, (string)$ref_method, (string)$ref_route);
            } else {
                $this->setMiddleware($actionMiddleware);
            }
        } else {
            $route = $args[0];
            $action = $args[1];
            if (!isset($action) || !is_callable($action)) {
                return false;
            }
            $this->routes[$method][$route] = $action;
            $this->currentCallReference = array($method, $route);
        }
        return true;
    }

    /**
     * Allow method accept 'get', 'post', 'patch', 'put', 'delete', 'middleware'
     * @param string $method
     * @return bool
     */
    private function validate($method)
    {
        return in_array($method, array('get', 'post', 'patch', 'put', 'delete', 'middleware'));
    }

    /**
     * @param $middleware
     * @param bool $method
     * @param bool $route
     * @return void
     */
    public function setMiddleware(Closure $middleware, $method = false, $route = false)
    {
        if (($method !== false && $route !== false) && (!Helpers::stringIsOk($method) || !Helpers::stringIsOk($route))) {
            throw new InvalidArgumentException(sprintf(
                '$key must be a valid string [ok:%s-%s, ok:%s-%s] ',
                $method, $route, !Helpers::stringIsOk($method), !Helpers::stringIsOk($route)
            ));
        } elseif ($method !== false && $route !== false) {
            $key = sprintf("%s%s", $method, $route);
            $this->middlewares[$key][] = $middleware;
        } else {
            $this->middlewares['global'][] = $middleware;
        }
    }

    public function debugger()
    {
        var_dump($this->routes);
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
        $finalThis = $this->executeMiddleware($this->method, $this->route);
        die($finalThis->routes[$finalThis->method][$finalThis->route]($finalThis->request, $finalThis->response));
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
     * @param $method
     * @param $route
     * @return Router
     */
    private function executeMiddleware($method, $route)
    {
        $size = count($this->getMiddlewares());
        if ($size > 0) {
            $newThis = clone $this;
            $global = $this->getMiddlewares();
            $global = $global['global'];
            $callables = $this->getMiddlewareFrom($method, $route);
            $callables = array_merge(
                isset($callables) && count($callables) && is_array($callables) ? $callables : array(),
                isset($global) && count($global) && is_array($global) ? $global : array()
            );
            //var_dump($method, $route, $global, $this->getMiddlewareFrom($method, $route), $callables);
            foreach ($callables as $callable) {
                $callable(
                    $this->getRequest(),
                    $this->getResponse(),
                    function (Request $request, Response $response) use ($newThis) {
                        $newThis->setRequest($request);
                        $newThis->setResponse($response);
                    }
                );
            }
            return $newThis;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * @param $method
     * @param $route
     * @return array|InvalidArgumentException
     */
    private function getMiddlewareFrom($method, $route)
    {
        if (!Helpers::stringIsOk($method) || !Helpers::stringIsOk($route)) {
            return new InvalidArgumentException('invalid $method.$route offset');
        }
        $key = sprintf("%s%s", $method, $route);
        return $this->getMiddleware($key);
    }

    /**
     * @param $index
     * @return array
     */
    public function getMiddleware($index)
    {
        if (!Helpers::stringIsOk($index)) {
            throw new InvalidArgumentException('invalid $index offset');
        }
        return isset($this->middlewares[$index]) ? $this->middlewares[$index] : array();
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
