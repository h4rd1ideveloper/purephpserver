<?php

namespace Server;

use Exception;
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
 * @see callable
 * @see die
 * @see Request {@RequestInterface}
 * @see Response {@ResponseInterface}
 * @method get(string $string, callable $param, ?callable $closure = null)
 * @method post(string $string, callable $param, ?callable $closure = null)
 * @method patch(string $string, callable $param, ?callable $closure = null)
 * @method put(string $string, callable $param, ?callable $closure = null)
 * @method delete(string $string, callable $param, ?callable $closure = null)
 */
class Router
{

    /**
     * @var array
     */
    private static $params = [];
    /**
     * @var string
     */
    private static $BASE_ULR;
    /**
     * @var
     */
    private static $middleware;
    /**
     * @var array
     */
    private $routes = [];
    /**
     * @var array
     */
    private $INITIAL_STATE = [
        "path_root" => '',
        "show_errors" => true,
        "cors" => true
    ];
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
     * @return mixed|string
     */
    private static function routeFromGlobal()
    {
        return isset($_SERVER['REQUEST_URI']) ?
            HttpHelper::requestUriString(self::$BASE_ULR, $_SERVER['REQUEST_URI'], true) :
            '/';
    }

    /**
     * @return mixed
     */
    public static function getMiddleware()
    {
        return self::$middleware;
    }

    /**
     * @param mixed $middleware
     * @param string $key
     */
    public static function setMiddleware(callable $middleware, string $key): void
    {
        self::$middleware[$key] = $middleware;
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
        } elseif (isset($args[0], $args[1])) {
            [$route, $action] = $args;
            $middleware = $args[2] ?? null;
            if (isset($action, $route) && is_callable($action)) {
                if ($middleware !== null && is_callable($middleware)) {
                    $this->setMiddleware($middleware, strtolower("$method#|#$route"));
                }
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
        return in_array($method, array('get', 'post', 'patch', 'put', 'delete'));
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
        $middleware = self::$middleware[strtolower("$this->method#|#$route")] ?? function (Request $request) {
                return $request;
            };
        die($this->executeMiddleware($middleware, $this->request, $this->routes[$this->method][$route]));
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

    /**
     * @param callable $middleware
     * @param Request $request
     * @param callable $closure
     * @return Response
     */
    private function executeMiddleware(callable $middleware, Request $request, callable $closure): Response
    {
        return $closure($middleware($request));
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        return get_object_vars($this);
    }

}
