<?php


use App\lib\Helpers;
use DI\Container;

$container->set(
    'illuminate_db',
    fn(Container $container) => Helpers::setupIlluminateConnectionAsGlobal($container->get('db_settings'))
);
