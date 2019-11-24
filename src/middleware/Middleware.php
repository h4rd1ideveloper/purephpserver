<?php


namespace App\middleware;

use App\controller\AppController;
use Firebase\JWT\JWT;
use Lib\Helpers;
use Psr\Http\Message\Request;

class Middleware
{
    public static function authenticate(): callable
    {
        return static function (Request $request): Request {
            if (Helpers::stringIsOk($_SESSION['user']) ) {

            } else {
                AppController::redirect('login');
            }
            return $request;
        };
    }

}