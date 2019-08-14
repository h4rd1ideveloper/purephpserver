<?php

require_once './../routes/index.php';


/**
 * Class Controller
 */
abstract class Controller {
    /**
     * @param string $_name
     * @param array $vars
     */
    protected static final function view( $_name, array $vars = []) {
        !file_exists( sprintf("./../view/%s.php",  $_name) ) && die("View {$_name} not found!");
        include_once ( sprintf("./../view/%s.php",  $_name) );
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