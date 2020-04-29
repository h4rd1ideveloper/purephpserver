<?php


use App\lib\Helpers;

$container->set('illuminate_db', Helpers::setupIlluminateConnectionAsGlobal($container->get('db_settings')));
