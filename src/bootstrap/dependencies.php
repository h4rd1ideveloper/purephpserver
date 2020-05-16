<?php


use App\lib\Helpers;
use DI\Container;

$container->set(
    'illuminate_db',
    fn(Container $c) => Helpers::setupIlluminateConnectionAsGlobal(
        $c->get('db_settings')
    )
);
$container->get('illuminate_db');