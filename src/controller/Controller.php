<?php

namespace App\controller;

use App\routes\Router;

/**
 * Class controller
 */
abstract class Controller
{
    /**
     * view($_name, $vars = array()):include_once(sprintf("%s/../view/%s.php", __DIR__, $_name));
     * @param string $_name
     * @param array $vars
     */
    public static final function view($_name, $vars = array())
    {
        !file_exists(sprintf("%s/../view/%s.php", __DIR__, $_name)) && die(print_r(array(sprintf('[%s]', sprintf("%s/../view/%s.php", __DIR__, $_name)), "view {$_name} not found!", __DIR__)));
        include_once(sprintf("%s/../view/%s.php", __DIR__, $_name));
    }

    /**
     * request() :[$_GET | $_POST]
     * @return array | null
     * @see Router::getRequest()
     */
    protected static final function request()
    {
        return (Router::getRequest()) ? Router::getRequest() : null;
    }

    /**
     * getRequestBody() :file_get_contents("php://input")
     * @return mixed
     */
    protected static final function getRequestBody()
    {
        return Router::getRequestBody();
    }

    /**
     * redirect($to)
     * @param string $to
     */
    protected static final function redirect($to)
    {
        $url = sprintf("%s://%s", isset($_SERVER['HTTPS']) ? 'https' : 'http', $_SERVER['HTTP_HOST']);
        $becauseIsOldVersionOfThePHP = explode('?', $_SERVER['REQUEST_URI']);
        $folders = $becauseIsOldVersionOfThePHP[0];
        header(sprintf("Location:%s%s?r=%s", $url, $folders, $to));
        exit();
    }

    /**
     * getUrlParams($key = ""):$_GET['key']
     * @param string $key
     * @return mixed
     */
    protected final function getUrlParams($key = "")
    {
        $becauseIsOldVersionOfThePHP = Router::getUrlParams();
        return ($key === "") ? Router::getUrlParams() : isset($becauseIsOldVersionOfThePHP[$key]) ? $becauseIsOldVersionOfThePHP[$key] : array();
    }

    /**
     * requestObject() :$_REQUEST
     * @return mixed
     */
    protected final function requestObject()
    {
        return Router::requestObject();
    }

    /**
     * serverObject() : $_SERVER
     * @return mixed
     */
    protected final function serverObject()
    {
        return Router::serverObject();
    }
}
