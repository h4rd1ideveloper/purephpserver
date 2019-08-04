<?php

require_once __DIR__ . './../routes/index.php';


/**
 * Class Controller
 */
abstract class Controller {
    /**
     * @param string $_name
     * @param array $vars
     */
    protected static final function view( $_name, array $vars = []) {
        !file_exists( sprintf("%s./../view/%s.php", __DIR__, $_name) ) && die("View {$_name} not found!");
        include_once ( sprintf("%s./../view/%s.php", __DIR__, $_name) );
    }
    /**
     * @param string $name
     * @return array|null
     */
    protected final function params($name) {
        return !( isset( Router::getRequest()[$name] ) ) ? null : Router::getRequest()[$name];
    }
    /**
     * @see Router::getRequest()
     * @return array | null
     */
    protected static final function request() {
        return ( Router::getRequest() ) ? Router::getRequest() : null;
    }
    /**
     * @param string $to
     */
    protected final function redirect( $to) {
        $url = sprintf("%s://%s", isset($_SERVER['HTTPS']) ? 'https' : 'http', $_SERVER['HTTP_HOST']);
        $folders = explode('?', $_SERVER['REQUEST_URI'])[0];
        header(sprintf("Location:%s%s?r=%s", $url, $folders, $to));
        exit();
    }
}