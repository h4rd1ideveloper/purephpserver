<?php

require_once __DIR__ . '.\..\routes\index.php';


/**
 * Class Controller
 */
abstract class Controller {
    /**
     * @param string $_name
     * @param array $vars
     */
    protected final function view(string $_name, array $vars = []) {
        $_filename = __DIR__.".\..\\view\\".$_name.".php";

        if(!file_exists($_filename))
            die("View {$_name} not found!");

        include_once $_filename;
    }

    /**
     * @param string $name
     * @return |null
     */
    protected final function params(string $name) {
        $params = Router::getRequest();

        if(!isset($params[$name]))
            return null;
        return $params[$name];
    }

    /**
     * @param string $to
     */
    protected final function redirect(string $to) {
        $url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
        $folders = explode('?', $_SERVER['REQUEST_URI'])[0];

        header('Location:' . $url . $folders . '?r=' . $to);
        exit();
    }
}