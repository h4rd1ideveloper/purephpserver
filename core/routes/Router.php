<?php

namespace Server;

use App\middleware\Middleware;
use Closure;
use Exception;
use InvalidArgumentException;
use Lib\Factory;
use Lib\Helpers;
use Monolog\Logger;
use Psr\Http\Message\HttpHelper;
use Psr\Http\Message\Request;
use Psr\Http\Message\Response;


/**
 * Class Router
 * @author Yan Santos Policar <policarpo@ice.ufjf.br>
 * @version 1.1.0
 * @see Closure
 * @see die
 * @see Request {@RequestInterface}
 * @see Response {@ResponseInterface}
 * @method get(string $string, Closure $param, ?Closure|array $closure = false)
 * @method post(string $string, Closure $param, ?Closure|array $closure = false)
 * @method patch(string $string, Closure $param, ?Closure|array $closure = false)
 * @method put(string $string, Closure $param, ?Closure|array $closure = false)
 * @method delete(string $string, Closure $param, ?Closure|array $closure = false)
 * @method add(Closure|array $param)
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
     * @var Middleware|null
     */
    private $middleware = null;
    /**
     * @var array
     */
    private $routes = array();
    /**
     * @var array
     */
    private $INITIAL_STATE = array(
        "path_root" => '',
        "show_errors" => true,
        "cors" => true
    );
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
        $this->request = HttpHelper::requestFromGlobalsFactory($this->method, self::versionFromGlobal());
        $this->middleware = new Middleware();
    }

    /**
     * @param $config
     */
    private function init($config)
    {
        if (!$config) {
            self::$BASE_ULR = $this->INITIAL_STATE["path_root"];
            $this->INITIAL_STATE["show_errors"] === true && Helpers::showErrors();
            $this->INITIAL_STATE["cors"] === true && Helpers::cors();
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
     * @param string $message
     * @throws Exception
     */
    public function runException(string $message): void
    {
        try {
            $logger = new Logger('Runner');
            $logger->pushHandler(Factory::StreamHandlerFactory('Router_run.log', Logger::WARNING));
            $logger->critical('Fail to execute run Router ->' . $message, $this());
            echo $message;
            HttpHelper::setHeader("HTTP/1.0 500 Internal Server Error");
        } catch (Exception $e) {
            HttpHelper::setHeader("HTTP/1.0 500 Internal Server Error [$message]");
        }
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

        if ($method === 'add') {
            $actionMiddleware = $args[0];
            if (is_array($actionMiddleware)) {
                $this->addAnyMiddleware($actionMiddleware);
            } elseif (is_callable($actionMiddleware)) {
                if (
                    $this->currentCallReference !== null &&
                    isset($this->currentCallReference[0], $this->currentCallReference[1])
                ) {

                    $ref_method = $this->currentCallReference[0];
                    $ref_route = $this->currentCallReference[1];
                    $this->setMiddleware($actionMiddleware, (string)$ref_method, (string)$ref_route);

                } else {

                    $this->setMiddleware($actionMiddleware);
                }
            } else {
                return false;
            }
        } elseif (isset($args[1], $args[0])) {
            $route = $args[0];
            $action = $args[1];
            $middleware = isset($args[2]) ? $args[2] : null;
            if (isset($action, $route) && is_callable($action)) {
                if ($middleware !== null && is_callable($middleware)) {
                    $this->setMiddleware($middleware, $method, $route);
                } elseif (is_array($middleware)) {
                    $this->addAnyMiddleware($middleware);
                }
                $this->routes[$method][$route] = $action;
                $this->currentCallReference = [$method, $route];
            } else {
                return false;
            }
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
        return in_array($method, array('get', 'post', 'patch', 'put', 'delete', 'add'));
    }

    /**
     * @param array $actionMiddleware
     */
    private function addAnyMiddleware(array $actionMiddleware)
    {
        foreach ($actionMiddleware as $middlewareFunction) {
            if (!is_callable($middlewareFunction)) {
                throw new InvalidArgumentException("Pass a array of Closures in method middleware");
            }
            if ($this->currentCallReference !== null) {
                if (isset($this->currentCallReference[0])) {
                    $ref_method = $this->currentCallReference[0];
                }
                if (isset($this)) {
                    $ref_route = $this->currentCallReference[1];
                }
                if (isset($ref_method, $ref_route)) {
                    $this->setMiddleware($middlewareFunction, $ref_method, $ref_route);
                }
            } else {
                $this->setMiddleware($middlewareFunction);
            }
        }
    }

    /**
     * @param $middleware
     * @param bool $method
     * @param bool $route
     * @return void
     */
    public function setMiddleware(Closure $middleware, $method = false, $route = false): void
    {
        if (($method !== false && $route !== false) && (!Helpers::stringIsOk($method) || !Helpers::stringIsOk($route))) {
            throw new InvalidArgumentException(sprintf(
                '$key must be a valid string [ok:%s-%s, ok:%s-%s] ',
                $method, $route, !Helpers::stringIsOk($method), !Helpers::stringIsOk($route)
            ));

        }
        if ($method !== false && $route !== false) {
            $key = sprintf("%s%s", $method, $route);
            $this->middleware::addClosure($middleware, $key);
        } else {

            $this->middleware::addClosure($middleware);
        }
    }

    /**
     *
     */
    public function debugger()
    {
        var_dump($this->routes);
    }

    /**
     * Closure Executor
     * RUN
     * @throws Exception
     */
    public function run()
    {
        $this->validateMethodOrFail();
        $route = $this->validateRouteOrFail();
        self::$params = HttpHelper::getBodyByMethod($this->request);
        //$finalThis->routes[$finalThis->method][$route]($finalThis->request)
        die($this->middleware::executeMiddleware($route, $this->request, $this->routes[$this->method][$route]));
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
            die(function () {
                HttpHelper::setHeader('HTTP/1.0 405 Method Not Allowed');
                return 'Method Not Allowed';
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
            die(function () {
                HttpHelper::setHeader('HTTP/1.0 404  Request Route Not Found');
                return 'Route Not Found';
            });
        }
        return isset($this->routes[$this->method][$this->route]) ? $this->route : $alternativeRoute;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        return get_object_vars($this);
    }
}
