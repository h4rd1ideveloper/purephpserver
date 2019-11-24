<?php


namespace App\middleware;

use App\controller\AppController;
use Lib\Helpers;
use Lib\Token;
use Psr\Http\Message\Request;

class Middleware
{
    public static function authenticate(): callable
    {
        return static function (Request $request): Request {
            $token = str_replace('Bearer', '', $request->getHeaderLine('Authorization'));
            if (Helpers::stringIsOk($token) && Token::isValidToKey($token, '')) {
            $token = Token::decode($token,'');

            } else {
                AppController::redirect('login');
            }
            return $request;
        };
    }

}