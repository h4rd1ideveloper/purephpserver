<?php

use App\lib\Helpers as _;
use App\lib\Logger;
use DI\Container;

/**@var $container Container */
$container->set('illuminate_db', fn(Container $c) => _::setupIlluminateConnectionAsGlobal($c->get('db_settings')));
_::tryCatch(
    fn() => $container->get('illuminate_db'),
    function (Exception $exception) use($container) {
        $payload = ['db_settings' => _::tryCatch(fn() => $container->get('db_settings'), '-')];
        $message = _::exceptionErrorMessage($exception);
        Logger::errorLog($message,'tryCatch_get_illuminate_db',$payload);
        die($message);
    }
);