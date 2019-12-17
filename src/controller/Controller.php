<?php

namespace App\controller;

use Lib\HttpHelper;

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
     * redirect($to)
     * @param string $to
     */
    public static final function redirect($to)
    {
        $url = sprintf("%s://%s", isset($_SERVER['HTTPS']) ? 'https' : 'http', $_SERVER['HTTP_HOST']);
        $becauseIsOldVersionOfThePHP = explode('?', $_SERVER['REQUEST_URI']);
        $folders = $becauseIsOldVersionOfThePHP[0];
        HttpHelper::setHeader(sprintf("Location: %s%s%s", $url, $folders, $to));
    }
}
