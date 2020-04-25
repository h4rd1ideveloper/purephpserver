<?php


use App\lib\Helpers;
use Psr\Container\ContainerInterface;

$container->set('illuminate_db', fn(ContainerInterface $container) => Helpers::setupIlluminateConnectionAsGlobal($container->get('db_settings')));